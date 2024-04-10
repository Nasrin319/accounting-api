<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class CurrencyControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware; 
    
    public function it_can_store_exchange_rate()
    {
        // Create fake data for the request
        $data = [
            'type' => 'usd',
            'rate' => 500000,
            'amount' => 1000,
        ];

        // Send POST request to the store endpoint
        $response = $this->post('/exchange-currency', $data);

        // Assert that the request was successful (status code 200)
        $response->assertStatus(200);

        // Assert that the exchange rate was stored in the database
        $this->assertDatabaseHas('currency_exchange_rates', $data);
    }
}