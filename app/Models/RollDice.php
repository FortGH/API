<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RollDice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dice_1',
        'dice_2',
        'result',
    ];

    protected $hidden = [
        'updated_at'
    ];
}
