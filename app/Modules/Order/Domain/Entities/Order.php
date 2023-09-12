<?php

namespace App\Modules\Order\Domain\Entities;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Order\Domain\Enum\Status;
use App\Modules\Product\Domain\Entities\Product;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Order\Domain\Database\Factories\OrderFactory;

/**
 * @property int $id
 * @property Status $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection|Product[] $products
 * @property int $sub_total
 */
class Order extends Model
{
    use HasFactory;

    protected $fillable = ['status'];

    protected $casts = [
        'status' => Status::class,
    ];

    protected static function newFactory(): OrderFactory|\Illuminate\Database\Eloquent\Factories\Factory
    {
        return OrderFactory::new();
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot(['quantity', 'unit_price']);
    }

    public function subTotal(): Attribute
    {
        return Attribute::get(function () {
            return $this->products ? $this->products->reduce(function ($total, $product) {
                $total += $product->pivot->quantity * $product->pivot->unit_price;

                return $total;
            }, 0) : 0;
        });
    }
}
