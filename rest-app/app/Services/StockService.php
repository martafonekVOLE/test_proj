<?php

namespace App\Services;

use App\WeatherEnum;
use Carbon\CarbonImmutable;
use Illuminate\Http\Client\Factory;

class StockService
{
    private const API_URL = "https://yahoo-finance15.p.rapidapi.com/api/v1/markets/stock/modules?ticker=%s&module=financial-data";

    /**
     * @param \Illuminate\Http\Client\Factory $factory
     */
    public function __construct(
        private readonly Factory $factory,
    ){
    }

    /**
     * @param string $ticker
     * @return float
     * @throws \Illuminate\Http\Client\ConnectionException
     * @throws \Exception
     */
    public function getStockPrice(string $ticker): float
    {
        $url = sprintf(self::API_URL, $ticker, env('ALPHA_VANTAGE_API_KEY'));

        $response = $this->factory->withHeaders([
            'x-rapidapi-host' => 'yahoo-finance15.p.rapidapi.com',
            'x-rapidapi-key' => env('RAPID_API_KEY'),
        ])->get($url);

        if($response->failed()) {
            throw new \Exception('Unable to retrieve stock price: ' . $ticker);
        }

        return $this->parseStockPrice($response->json()['body']);
    }

    /**
     * @param array $response
     * @return float
     */
    private function parseStockPrice(array $response): float
    {
        return (float) $response['currentPrice']['raw'];

//        return [
//            'ticker' => $response['01. symbol'],
//            'price' => $response['05. price'],
//            'retrieved_at' => CarbonImmutable::now()->format('Y-m-d H:i:s'),
//            (float) $response['05. price']
//        ];
    }
}
