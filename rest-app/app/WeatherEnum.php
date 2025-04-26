<?php

namespace App;

enum WeatherEnum: int
{
    case ClearSky = 0;
    case MainlyClear = 1;
    case PartlyCloudy = 2;
    case Overcast = 3;
    case Fog = 45;
    case DepositingRimeFog = 48;
    case LightDrizzle = 51;
    case ModerateDrizzle = 53;
    case DenseDrizzle = 55;
    case FreezingLightDrizzle = 56;
    case FreezingDenseDrizzle = 57;
    case LightRain = 61;
    case ModerateRain = 63;
    case HeavyRain = 65;
    case LightFreezingRain = 66;
    case HeavyFreezingRain = 67;
    case SlightSnow = 71;
    case ModerateSnow = 73;
    case HeavySnow = 75;
    case SnowGrains = 77;
    case SlightRainShowers = 80;
    case ModerateRainShowers = 81;
    case ViolentRainShowers = 82;
    case SlightSnowShowers = 85;
    case HeavySnowShowers = 86;
    case SlightThunderstorm = 95;
    case ModerateThunderstorm = 96;
    case SlightHailThunderstorm = 99;

    public static function toCzech(WeatherEnum $condition): string
    {
        return match ($condition) {
            self::ClearSky => 'Jasno',
            self::MainlyClear => 'Převážně jasno',
            self::PartlyCloudy => 'Polojasno',
            self::Overcast => 'Zataženo',
            self::Fog => 'Mlha',
            self::DepositingRimeFog => 'Mlha s námrazou',
            self::LightDrizzle => 'Mrholení: slabé',
            self::ModerateDrizzle => 'Mrholení: střední',
            self::DenseDrizzle => 'Mrholení: silné',
            self::FreezingLightDrizzle => 'Mrholení: slabé a namrzající',
            self::FreezingDenseDrizzle => 'Mrholení: silné a namrzající',
            self::LightRain => 'Déšť: slabý',
            self::ModerateRain => 'Déšť: střední',
            self::HeavyRain => 'Déšť: silný',
            self::LightFreezingRain => 'Déšť: slabý a namrzající',
            self::HeavyFreezingRain => 'Déšť: silný a namrzající',
            self::SlightSnow => 'Sněžení: slabé',
            self::ModerateSnow => 'Sněžení: střední',
            self::HeavySnow => 'Sněžení: silné',
            self::SnowGrains => 'Sněhové vločky',
            self::SlightRainShowers => 'Dešťové přeháňky: slabé',
            self::ModerateRainShowers => 'Dešťové přeháňky: střední',
            self::ViolentRainShowers => 'Dešťové přeháňky: silné',
            self::SlightSnowShowers => 'Sněhové přeháňky: slabé',
            self::HeavySnowShowers => 'Sněhové přeháňky: silné',
            self::SlightThunderstorm => 'Bouřka: slabá',
            self::ModerateThunderstorm => 'Bouřka: střední',
            self::SlightHailThunderstorm => 'Bouřka s krupobitím',
        };
    }
}

