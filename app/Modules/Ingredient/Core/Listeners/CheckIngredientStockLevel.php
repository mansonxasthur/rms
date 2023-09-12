<?php

namespace App\Modules\Ingredient\Core\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\Order\Core\Events\OrderCreated;
use App\Modules\Product\Domain\Entities\Product;
use App\Modules\Ingredient\Domain\Entities\Inventory;
use App\Modules\Ingredient\Domain\Entities\Ingredient;
use App\Modules\Ingredient\Core\Events\InventoryUnderThreshold;

class CheckIngredientStockLevel implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $event->order->products->each(function (Product $product) {
            $product->ingredients->each(function (Ingredient $ingredient) {
                if ($this->underThreshold($ingredient->inventory)) {
                    event(new InventoryUnderThreshold($ingredient->inventory));
                }
            });
        });
    }

    private function underThreshold(Inventory $inventory): bool
    {
        return $inventory->current_level_percentage <= $inventory->threshold;
    }
}
