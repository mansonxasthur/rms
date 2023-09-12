<?php

namespace App\Modules\Order\Core\Services;

use App\Modules\Ingredient\Core\Services\UpdateCurrentStockLevelService;
use App\Modules\Order\Domain\Entities\Order;
use App\Modules\Order\Domain\Enum\Status;
use App\Modules\Order\Domain\Repositories\OrderRepository;
use App\Modules\Product\Domain\Entities\Product;
use App\Modules\Product\Domain\Repositories\ProductRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class CreateOrderService
{
    public function __construct(
        private readonly OrderRepository                $orderRepository,
        private readonly ProductRepository              $productRepository,
        private readonly UpdateCurrentStockLevelService $currentStockLevelService,
    )
    {
    }

    public function create(array $orderProducts): Order
    {
        return DB::transaction(function () use ($orderProducts) {
            $orderProducts = array_reduce($orderProducts, function ($ids, $p) {
                $ids[$p['product_id']] = $p;

                return $ids;
            }, []);

            /** @var Order $order */
            $order = $this->orderRepository->create(['status' => Status::Pending]);
            $products = $this->productRepository->listByIds(array_keys($orderProducts));

            $order->products()
                ->attach(
                    $this->createAttachmentBody($products, $orderProducts)
                );
            foreach ($order->products as $product) {
                foreach ($product->ingredients as $ingredient) {
                    $requiredIngredientAmount = $ingredient->pivot->amount * $product->pivot->quantity;
                    $this->currentStockLevelService->handle($ingredient, $requiredIngredientAmount);
                }
            }
            return $order;
        });
    }

    public function createAttachmentBody(Collection $products, array $orderProducts): array
    {
        return $products->reduce(function (array $list, Product $product) use ($orderProducts) {
            $list[$product->id] = [
                'unit_price' => $product->price,
                'quantity'   => $orderProducts[$product->id]['quantity'],
            ];

            return $list;
        }, []);
    }
}
