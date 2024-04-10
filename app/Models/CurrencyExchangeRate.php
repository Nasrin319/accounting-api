<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyExchangeRate extends Model
{
    use HasFactory;

    protected $guraded = [];
    const TYPE = [
        'usd',
        'eur',
        // Add more currencies as needed
    ];
}
