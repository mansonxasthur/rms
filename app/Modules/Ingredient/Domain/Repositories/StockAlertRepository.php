<?php

namespace App\Modules\Ingredient\Domain\Repositories;

use Dust\Base\Repository;
use App\Modules\Ingredient\Domain\Entities\Inventory;
use App\Modules\Ingredient\Domain\Entities\StockAlert;

class StockAlertRepository extends Repository
{
    public function __construct(StockAlert $stockAlert)
    {
        parent::__construct($stockAlert);
    }

    public function hasActiveAlert(Inventory $inventory): bool
    {
        return $this->where('ingredient_id', $inventory->ingredient_id)
            ->whereNull('resolved_at')
            ->exists();
    }

    public function createAlert(Inventory $inventory): void
    {
        $this->create(['ingredient_id' => $inventory->ingredient_id, 'email_sent_at' => now()]);
    }
}
