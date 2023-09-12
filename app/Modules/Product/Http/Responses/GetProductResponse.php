<?php

namespace App\Modules\Product\Http\Responses;

use Dust\Base\Response;
use App\Modules\Product\Domain\Entities\Product;
use App\Modules\Product\Http\Resources\ProductResource;

class GetProductResponse extends Response
{
    /**
     * @param  Product  $resource
     */
    protected function createResource(mixed $resource): mixed
    {
        return new ProductResource($resource);
    }
}
