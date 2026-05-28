<?php

namespace App\Models;

use App\Enum\BudgetType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable('name', 'amount', 'type', 'user_id')]
class Budget extends Model
{
    /** @use HasFactory<\Database\Factories\BudgetFactory> */
    use HasFactory;
    use SoftDeletes;

    // protected $fillable = [
    //     'name',
    //     'amount',
    //     'type',
    //     'user_id',
    // ];


    protected function casts(): array
    {
        return [
            'type' => BudgetType::class,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isGeneral(): bool
    {
        return $this->type === BudgetType::General;
    }

    public function isGoal(): bool
    {
        return $this->type === BudgetType::Goal;
    }
}
