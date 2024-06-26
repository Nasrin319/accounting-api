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

    public function index(Request $request)
    {
        $exchangeRates = CurrencyExchangeRate::filter()->paginate(10);

        $exchangeRates->onEachSide(200);
        return (CurrencyExchangeRateResource::collection($exchangeRates));
    }
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
        // $exchangeRate = CurrencyExchangeRate::create([$request->all()]);
        $exchangeRate = new CurrencyExchangeRate();
        $exchangeRate->type = $request->type;
        $exchangeRate->rate = $request->rate;
        $exchangeRate->amount = $request->amount;
        $exchangeRate->save();

        // Return a resource with the stored exchange rate data
        return new CurrencyExchangeRateResource($exchangeRate);
    }

    public function fetchOurAssets()
    {

        $transactions = CurrencyExchangeRate::all();

        $totalValue = 0;
        $totalAmount = 0;

        foreach ($transactions as $transaction) {
            $totalValue += $transaction->amount * $transaction->rate;
            $totalAmount += $transaction->amount;
        }
        if ($totalAmount > 0) {
            $weightedAverageRate = $totalValue / $totalAmount;
            return $this->successResponse(['our-amount' => $totalAmount, 'weightedAverageRate' => $weightedAverageRate]);
        } else {
            return $this->errorResponse("No transactions found to calculate the weighted average rate.", 400);
        }
    }
}
