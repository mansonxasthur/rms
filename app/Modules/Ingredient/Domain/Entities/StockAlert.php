<?php

namespace App\Modules\Ingredient\Domain\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Ingredient\Domain\Database\Factories\StockAlertFactory;

/**
 * @property int $id
 * @property int $ingredient_id
 * @property Carbon $email_sent_at
 * @property Carbon $resolved_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class StockAlert extends Model
{
    use HasFactory;

    protected $casts = [
        'email_sent_at' => 'timestamp',
        'resolved_at' => 'timestamp',
    ];

    protected $fillable = ['ingredient_id', 'email_sent_at', 'resolved_at'];

    protected static function newFactory()
    {
        return StockAlertFactory::new();
    }
}
