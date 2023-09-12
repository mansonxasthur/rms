<?php

namespace App\Modules\Order\Core\Exceptions;

use App\Modules\Ingredient\Core\Exceptions\Throwable;
use App\Modules\Ingredient\Domain\Entities\Inventory;
use Exception;

class InsufficientIngredientStockLevel extends Exception
{
    public function __construct(Inventory $inventory, int $requiredAmount, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($this->createMessage($inventory, $requiredAmount), $code, $previous);
    }

    private function createMessage(Inventory $inventory, int $requiredAmount)
    {
        return sprintf("Insufficient stock level [%d]%s for ingredient with id [%d], required amount [%d]%s",
            $inventory->current_level, $inventory->unit->name, $inventory->ingredient_id, $requiredAmount, $inventory->unit->name);
    }
}
