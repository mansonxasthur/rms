<?php

namespace App\Modules\Order\Domain\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Order\Domain\Entities\Order>
 */
class OrderFactory extends Factory
{
    protected $model = \App\Modules\Order\Domain\Entities\Order::class;

    public function definition(): array
    {
        return [
            //
        ];
    }
}
