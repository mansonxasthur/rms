<?php

namespace App\Modules\Order\Domain\Repositories;

use Dust\Base\Repository;
use App\Modules\Order\Domain\Entities\Order;

class OrderRepository extends Repository
{
    public function __construct(Order $order)
    {
        parent::__construct($order);
    }
}
