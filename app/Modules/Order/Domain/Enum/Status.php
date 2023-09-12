<?php

namespace App\Modules\Order\Domain\Enum;

enum Status: int
{
    case Pending = 1;
    case Ready = 2;
}
