<?php

namespace App\Modules\Ingredient\Domain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Ingredient\Domain\Database\Factories\IngredientFactory;

/**
 * @property int $id
 * @property string $name
 * @property Inventory $inventory
 */
class Ingredient extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory|IngredientFactory
    {
        return IngredientFactory::new();
    }

    public function inventory(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Inventory::class);
    }
}
