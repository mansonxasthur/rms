<?php

namespace App\Modules\Product\Http\Resources;

use Illuminate\Http\Request;
use App\Modules\Product\Domain\Entities\Product;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Ingredient\Http\Resources\IngredientResource;

/**
 * @mixin Product
 */
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->whenPivotLoaded('order_product', $this->pivot?->unit_price, $this->price),
            'ingredients' => IngredientResource::collection($this->whenLoaded('ingredients')),
            'quantity' => $this->whenPivotLoaded('order_product', $this->pivot?->quantity),
        ];
    }
}
