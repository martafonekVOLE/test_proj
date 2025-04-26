<?php

namespace App\Services;

use App\WeatherEnum;
use Carbon\CarbonImmutable;
use Illuminate\Http\Client\Factory;

class StockService
{
    private const API_URL = "https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol=%s&apikey=%s";

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

        $response = $this->factory->get($url);

        if($response->failed()) {
            throw new \Exception('Unable to retrieve stock price: ' . $ticker);
        }

        return $this->parseStockPrice($response->json()['Global Quote']);
    }

    /**
     * @param array $response
     * @return float
     */
    private function parseStockPrice(array $response): float
    {

        return (float) $response['05. price'];

//        return [
//            'ticker' => $response['01. symbol'],
//            'price' => $response['05. price'],
//            'retrieved_at' => CarbonImmutable::now()->format('Y-m-d H:i:s'),
//            (float) $response['05. price']
//        ];
    }
}
