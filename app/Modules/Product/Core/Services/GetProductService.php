<?php

namespace App\Modules\Product\Core\Services;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Product\Domain\Entities\Product;
use App\Modules\Product\Domain\Repositories\ProductRepository;

final class GetProductService
{
    public function __construct(protected ProductRepository $productRepository)
    {
    }

    public function get(int $productId): Product|Model|null
    {
        return $this->productRepository
            ->with('ingredients')
            ->firstOrFail();
    }
}
