<?php

namespace App\Modules\Ingredient\Domain\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Ingredient\Domain\Enum\Unit;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Ingredient\Domain\Database\Factories\InventoryFactory;

/**
 * @property int $id
 * @property int $ingredient_id
 * @property int $stock_level
 * @property int $current_level
 * @property Unit $unit
 * @property int $threshold // threshold in percentage
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $current_level_percentage
 * @property Ingredient $ingredient
 */
class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $casts = [
        'unit' => Unit::class,
    ];

    protected static function newFactory(): InventoryFactory|\Illuminate\Database\Eloquent\Factories\Factory
    {
        return InventoryFactory::new();
    }

    public function currentLevelPercentage(): Attribute
    {
        return Attribute::get(fn () => round(($this->current_level * 100) / $this->stock_level));
    }

    public function ingredient(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }
}
