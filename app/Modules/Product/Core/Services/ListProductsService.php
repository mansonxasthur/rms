<?php

namespace App\Modules\Product\Core\Services;

use App\Modules\Product\Domain\Repositories\ProductRepository;

final class ListProductsService
{
    public function __construct(protected ProductRepository $productRepository)
    {
    }

    public function paginate(int $perPage): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->productRepository
            ->with('ingredients')
            ->paginate($perPage);
    }
}
