<?php

namespace App\Modules\Order\Tests\Feature;

use App\Modules\Ingredient\Core\Notifications\IngredientStockAlertNotification;
use App\Modules\Ingredient\Domain\Entities\Ingredient;
use App\Modules\Ingredient\Domain\Entities\Inventory;
use App\Modules\Ingredient\Domain\Entities\StockAlert;
use App\Modules\Order\Core\Events\OrderCreated;
use App\Modules\Order\Core\Exceptions\InsufficientIngredientStockLevel;
use App\Modules\Order\Domain\Enum\Status;
use App\Modules\Product\Domain\Entities\Product;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CreateOrderTest extends TestCase
{
    /**
     * @test
     *
     * @group order
     */
    public function it_can_create_order(): void
    {
        Event::fake(OrderCreated::class);
        $product = Product::factory()
            ->state(['name' => 'Burger', 'price' => '100.50'])
            ->create();

        $this->assertDatabaseCount('products', 1);
        $res = $this->json('post', route('api.orders.create'), [
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $res->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseHas('orders', ['status' => Status::Pending->value]);
        Event::assertDispatched(OrderCreated::class);
    }

    /**
     * @test
     *
     * @group order
     */
    public function it_deduct_product_ingredient_amount_from_ingredient_inventory_current_level()
    {
        $beef = Ingredient::factory()->state(['name' => 'Beef'])
            ->has(Inventory::factory()->state(['stock_level' => 20000, 'current_level' => 20000]))
            ->create();
        $cheese = Ingredient::factory()->state(['name' => 'Cheese'])
            ->has(Inventory::factory()->state(['stock_level' => 5000, 'current_level' => 5000]))
            ->create();
        $onion = Ingredient::factory()->state(['name' => 'Onion'])
            ->has(Inventory::factory()->state(['stock_level' => 1000, 'current_level' => 1000]))
            ->create();

        $this->assertDatabaseCount('ingredients', 3);
        $this->assertDatabaseCount('inventory', 3);
        $product = Product::factory()
            ->state(['name' => 'Burger', 'price' => '100.50'])
            ->create();

        $this->assertDatabaseCount('products', 1);
        $product->ingredients()->attach([
            $beef->id => ['amount' => 150],
            $cheese->id => ['amount' => 30],
            $onion->id => ['amount' => 20],
        ]);
        $this->assertDatabaseCount('ingredient_product', 3);
        $res = $this->json('post', route('api.orders.create'), [
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $res->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseHas('orders', ['status' => Status::Pending->value]);

        $this->assertDatabaseHas('inventory', ['ingredient_id' => $beef->id, 'current_level' => 20000 - (2 * 150)]);
        $this->assertDatabaseHas('inventory', ['ingredient_id' => $cheese->id, 'current_level' => 5000 - (2 * 30)]);
        $this->assertDatabaseHas('inventory', ['ingredient_id' => $onion->id, 'current_level' => 1000 - (2 * 20)]);
    }

    /**
     * @test
     *
     * @group order
     */
    public function it_alerts_merchant_for_under_threshold_ingredient_stock_level()
    {
        Notification::fake();
        $beef = Ingredient::factory()->state(['name' => 'Beef'])
            ->has(Inventory::factory()->state(['stock_level' => 1000, 'current_level' => 1000]))
            ->create();

        $this->assertDatabaseCount('ingredients', 1);
        $this->assertDatabaseCount('inventory', 1);
        $product = Product::factory()
            ->state(['name' => 'Burger', 'price' => '100.50'])
            ->create();

        $this->assertDatabaseCount('products', 1);
        $product->ingredients()->attach([
            $beef->id => ['amount' => 150],
        ]);
        $this->assertDatabaseCount('ingredient_product', 1);

        $res = $this->json('post', route('api.orders.create'), [
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 4,
                ],
            ],
        ]);

        $res->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseHas('orders', ['status' => Status::Pending->value]);

        $this->assertDatabaseHas('inventory', ['ingredient_id' => $beef->id, 'current_level' => 1000 - (4 * 150)]);
        $this->assertDatabaseHas('stock_alerts', ['ingredient_id' => $beef->id, 'resolved_at' => null]);
        Notification::assertSentOnDemand(IngredientStockAlertNotification::class);
    }

    /**
     * @test
     *
     * @group order
     */
    public function it_alerts_merchant_for_under_threshold_ingredient_stock_level_only_once()
    {
        Notification::fake();

        $beef = Ingredient::factory()->state(['name' => 'Beef'])
            ->has(Inventory::factory()->state(['stock_level' => 1000, 'current_level' => 500]))
            ->create();

        $this->assertDatabaseCount('ingredients', 1);
        $this->assertDatabaseCount('inventory', 1);

        $product = Product::factory()
            ->state(['name' => 'Burger', 'price' => '100.50'])
            ->create();

        $this->assertDatabaseCount('products', 1);
        $product->ingredients()->attach([
            $beef->id => ['amount' => 150],
        ]);

        $this->assertDatabaseCount('ingredient_product', 1);

        StockAlert::factory()->create(['ingredient_id' => $beef->id, 'email_sent_at' => now()]);
        $this->assertDatabaseCount('stock_alerts', 1);

        // Test it sends a notification to merchant after catching threshold
        $res = $this->json('post', route('api.orders.create'), [
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                ],
            ],
        ]);

        $res->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseHas('orders', ['status' => Status::Pending->value]);

        $this->assertDatabaseHas('inventory', ['ingredient_id' => $beef->id, 'current_level' => 500 - 150]);
        $this->assertDatabaseCount('stock_alerts', 1);
        $this->assertDatabaseHas('stock_alerts', ['ingredient_id' => $beef->id, 'resolved_at' => null]);
        Notification::assertSentOnDemandTimes(IngredientStockAlertNotification::class, 0);
    }

    /**
     * @test
     *
     * @group order
     */
    public function it_alerts_merchant_for_under_threshold_stock_level_for_multiple_ingredients()
    {
        Notification::fake();
        $beef = Ingredient::factory()->state(['name' => 'Beef'])
            ->has(Inventory::factory()->state(['stock_level' => 1000, 'current_level' => 1000]))
            ->create();
        $cheese = Ingredient::factory()->state(['name' => 'Cheese'])
            ->has(Inventory::factory()->state(['stock_level' => 200, 'current_level' => 200]))
            ->create();

        $this->assertDatabaseCount('ingredients', 2);
        $this->assertDatabaseCount('inventory', 2);
        $product = Product::factory()
            ->state(['name' => 'Burger', 'price' => '100.50'])
            ->create();

        $this->assertDatabaseCount('products', 1);
        $product->ingredients()->attach([
            $beef->id => ['amount' => 150],
            $cheese->id => ['amount' => 30],
        ]);
        $this->assertDatabaseCount('ingredient_product', 2);

        $res = $this->json('post', route('api.orders.create'), [
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 4,
                ],
            ],
        ]);

        $res->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseHas('orders', ['status' => Status::Pending->value]);

        $this->assertDatabaseHas('inventory', ['ingredient_id' => $beef->id, 'current_level' => 1000 - (4 * 150)]);
        $this->assertDatabaseHas('inventory', ['ingredient_id' => $cheese->id, 'current_level' => 200 - (4 * 30)]);
        $this->assertDatabaseHas('stock_alerts', ['ingredient_id' => $beef->id, 'resolved_at' => null]);
        $this->assertDatabaseHas('stock_alerts', ['ingredient_id' => $cheese->id, 'resolved_at' => null]);
        Notification::assertSentOnDemandTimes(IngredientStockAlertNotification::class, 2);
    }

    /**
     * @test
     *
     * @group order
     */
    public function it_fails_for_insufficient_ingredients()
    {
        $beef = Ingredient::factory()->state(['name' => 'Beef'])
            ->has(Inventory::factory()->state(['stock_level' => 1000, 'current_level' => 1000]))
            ->create();

        $this->assertDatabaseCount('ingredients', 1);
        $this->assertDatabaseCount('inventory', 1);
        $product = Product::factory()
            ->state(['name' => 'Burger', 'price' => '100.50'])
            ->create();

        $this->assertDatabaseCount('products', 1);
        $product->ingredients()->attach([
            $beef->id => ['amount' => 150],
        ]);
        $this->assertDatabaseCount('ingredient_product', 1);

        $res = $this->json('post', route('api.orders.create'), [
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 10,
                ],
            ],
        ]);

        $res->assertStatus(Response::HTTP_MOVED_PERMANENTLY);
        $res->assertJsonFragment(['message' => 'Insufficient ingredients to prepare the order!']);

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseHas('inventory', ['ingredient_id' => $beef->id, 'current_level' => 1000]);
        $this->assertDatabaseCount('stock_alerts', 0);
    }
}
