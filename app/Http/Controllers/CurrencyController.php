<?php

namespace App\Http\Controllers;

use App\Http\Resources\CurrencyExchangeRateResource;
use App\Models\CurrencyExchangeRate;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
    use ApiResponse;
    public function store(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:' . implode(',', array_keys(Config::get('currencies'))),
            'rate' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        }

        // Store exchange rate data in the database
        $exchangeRate = new CurrencyExchangeRate();
        $exchangeRate->currency_type = $request->type;
        $exchangeRate->exchange_rate = $request->rate;
        $exchangeRate->amount = $request->amount;
        $exchangeRate->save();

        // Return a resource with the stored exchange rate data
        return new CurrencyExchangeRateResource($exchangeRate);

    }
}
