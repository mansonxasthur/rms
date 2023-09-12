<?php

namespace App\Modules\Ingredient\Core\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Modules\Ingredient\Domain\Entities\Inventory;

class InventoryUnderThreshold
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Inventory $inventory)
    {
        //
    }
}
