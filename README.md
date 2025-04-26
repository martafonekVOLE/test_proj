# REST Service
author: *Martin Pech (567763)*

---
This application is the third homework for PA053, FI MUNI. 
It is focused on REST API.
>IMPORTANT: This application is incomplete (no auth required, ...), and it only serves one purpose - successfully finish school homework :) 

## How does it work?
This is a Laravel (PHP) application. It uses Laravel's MVC architecture pattern. This service does support three query parameters described here:
https://is.muni.cz/auth/el/1433/jaro2025/PA053/um/homework3.txt

Each request is validated by the application and based on the query parameter, different services handle them.
In order to achieve required behaviour, those third-party tools were used:
- **render.com** for deploy
- **airport-data.com** for airport data
- **yahoo-finance15.p.rapidapi.com** for stocks data
- **api.open-meteo.com** for weather data
>NOTE: in order to run this application, you need to obtain rapidapi api key.


## How to run
> NOTE: You must have **PHP 8.1+** and **composer** installed.

1. Open terminal and navigate to root directory (*rest-app*).
2. Copy .env.example to .env and fill `RAPID_API_KEY` with your **RAPIDAPI API KEY**.
3. Run `composer i` in order to install laravel and related packages.
4. Run `php artisan serve` in order to run the application. You will be provided with information about where the project is running (*default: http://127.0.0.1:8000*).


## Hosted app
This application is also hosted on the interner, you can see it here:
https://distributed-systems-project-yavl.onrender.com/api/service
