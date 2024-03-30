<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'price',
        'open',
        'high',
        'low',
        'volume',
        'previous_close',
        'change',
        'change_percent',
        'symbol',
        'date',
    ];
}
