<?php

namespace App\Modules\Ingredient\Core\Services;

use App\Modules\Ingredient\Domain\Entities\Ingredient;
use App\Modules\Ingredient\Domain\Entities\Inventory;
use App\Modules\Order\Core\Exceptions\InsufficientIngredientStockLevel;
use Throwable;

final class UpdateCurrentStockLevelService
{
    public function __construct()
    {
    }

    /**
     * @throws Throwable
     * @throws InsufficientIngredientStockLevel
     */
    public function handle(Ingredient $ingredient, int $requiredIngredientAmount): Inventory
    {
        try {
            $ingredient->inventory()->decrement('current_level', $requiredIngredientAmount);

            return $ingredient->inventory;
        } catch (Throwable $e) {
            if ($e->getCode() === "22003") {
                throw new InsufficientIngredientStockLevel($ingredient->inventory, $requiredIngredientAmount, $e->getCode());
            }
            throw $e;
        }
    }
}
