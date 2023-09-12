<?php

namespace App\Modules\Product\Http\Responses;

use Dust\Base\Response;
use App\Modules\Product\Http\Resources\ProductResource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListProductsResponse extends Response
{
    /**
     * @param  LengthAwarePaginator  $resource
     */
    protected function createResource(mixed $resource): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return ProductResource::collection($resource);
    }
}
