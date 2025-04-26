<?php

namespace App\Services;

use App\WeatherEnum;
use Carbon\CarbonImmutable;
use Illuminate\Http\Client\Factory;

class WeatherService
{
    private const API_URL = "https://api.open-meteo.com/v1/forecast";

    /**
     * @param \Illuminate\Http\Client\Factory $factory
     */
    public function __construct(
        private readonly Factory $factory,
    ){
    }

    /**
     * @param string $lat
     * @param string $lon
     * @return array
     * @throws \Illuminate\Http\Client\ConnectionException
     * @throws \Exception
     */
    public function getWeatherFor(string $lat, string $lon): array
    {
        $response = $this->factory->get(self::API_URL, [
            'latitude' => $lat,
            'longitude' => $lon,
            'current_weather' => 'true',
            'timezone' => 'auto',
        ]);

        if($response->failed()) {
            throw new \Exception('Unable to retrieve weather data for LAT: ' . $lat . ', LONG: ' . $lon);
        }

        return $this->parseWeatherInfo($response->json());
    }

    /**
     * @param array $response
     * @return array
     */
    private function parseWeatherInfo(array $response): array
    {
        $weather = WeatherEnum::tryFrom($response['current_weather']['weathercode']);
        if($weather !== null) {
            $weather = WeatherEnum::toCzech($weather);
        }

        return [
            $response['current_weather']['temperature']
        ];

//        return [
//            'measured_at' => CarbonImmutable::parse($response['current_weather']['time'])->format('Y-m-d H:i:s'),
//            'day_time' => $response['current_weather']['is_day'] ? 'day' : 'night',
//            'temperature' => $response['current_weather']['temperature'] . $response['current_weather_units']['temperature'],
//            'wind' => 'speed: ' . $response['current_weather']['windspeed'] . $response['current_weather_units']['windspeed'] . ', direction: ' . $response['current_weather']['winddirection'] . '°',
//            'weather_condition' => $weather,
//        ];
    }
}
