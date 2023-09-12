<?php

namespace App\Modules\Product\Domain\Repositories;

use Dust\Base\Repository;
use App\Modules\Product\Domain\Entities\Product;

class ProductRepository extends Repository
{
    public function __construct(Product $product)
    {
        parent::__construct($product);
    }

    public function listByIds(array $ids): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->whereIn('id', $ids)
            ->get();
    }
}
