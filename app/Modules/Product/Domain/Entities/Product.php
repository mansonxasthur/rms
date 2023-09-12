<?php

namespace App\Modules\Product\Domain\Entities;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Ingredient\Domain\Entities\Ingredient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Product\Domain\Database\Factories\ProductFactory;

/**
 * @property int $id
 * @property string $name
 * @property float $price
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection|Ingredient[] $ingredients
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price'];

    protected $casts = [
        'price' => 'float',
    ];

    protected static function newFactory(): ProductFactory|\Illuminate\Database\Eloquent\Factories\Factory
    {
        return ProductFactory::new();
    }

    public function ingredients(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class)->withPivot(['amount']);
    }
}
