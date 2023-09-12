<?php

namespace App\Modules\Order\Core\Events;

use Illuminate\Queue\SerializesModels;
use App\Modules\Order\Domain\Entities\Order;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class OrderCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Order $order)
    {
        //
    }
}
