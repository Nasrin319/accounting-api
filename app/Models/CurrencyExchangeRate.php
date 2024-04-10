<?php

namespace App\Models;

use App\Traits\FilterTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyExchangeRate extends Model
{
    use HasFactory,FilterTrait;


    public function scopeFilter(Builder $query, $filter = [])
    {
        $filter = $filter ?: request()->all();
        $filter = collect($filter);
        $query->filterColumn($filter, 'id');
        $query->filterColumn($filter, 'type');
        $query->filterStrColumn($filter, 'rate');
        return $query;
    }

    protected $guraded = [];
    const TYPE = [
        'usd',
        'eur',
        // Add more currencies as needed
    ];
}
