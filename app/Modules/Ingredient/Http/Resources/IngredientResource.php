<?php

namespace App\Modules\Ingredient\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Ingredient\Domain\Entities\Ingredient;

/**
 * @mixin Ingredient
 */
class IngredientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'amount' => $this->whenPivotLoaded('ingredient_product', function () {
                return $this->pivot->amount;
            }),
        ];
    }
}
