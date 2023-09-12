<?php

namespace App\Modules\Order\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Product\Http\Resources\ProductResource;

/**
 * @mixin \App\Modules\Order\Domain\Entities\Order
 */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status->name,
            'products' => ProductResource::collection($this->products),
            'sub_total' => $this->whenLoaded('products', $this->sub_total),
        ];
    }
}
