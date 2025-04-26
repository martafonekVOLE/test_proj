<?php

namespace App\Services;

use Illuminate\Http\Client\Factory;

class AirportService
{
    private const API_URL = "https://airport-data.com/api/ap_info.json?iata=%s";

    /**
     * @param \Illuminate\Http\Client\Factory $factory
     * @param \App\Services\WeatherService $weatherService
     */
    public function __construct(
        private readonly Factory $factory,
        private readonly WeatherService $weatherService,
    ){
    }

    /**
     * @param string $airport
     * @return array
     * @throws \Illuminate\Http\Client\ConnectionException
     * @throws \Exception
     */
    public function getAirportTemperature(string $airport): array {
        $url = sprintf(self::API_URL, $airport);

        $response = $this->factory->get($url);
        if($response->failed()) {
            throw new \Exception('Getting airport information failed');
        }

        $parsedResponse = $response->json();
        $weather = $this->weatherService->getWeatherFor($parsedResponse['latitude'], $parsedResponse['longitude']);
        $airportData = [
            'airport_name' => $parsedResponse['name'],
            'airport_iata' => $parsedResponse['iata'],
            'airport_location' => $parsedResponse['location'],
            'airport_country' => $parsedResponse['country'],
            'latitude' => $parsedResponse['latitude'],
            'longitude' => $parsedResponse['longitude'],
        ];

        return $weather;

//        return [
//            'airport_data' => $airportData,
//            'weather' => $weather,
//        ];
    }
}
