<?php

namespace App\Services;

use App\WeatherEnum;
use Carbon\CarbonImmutable;
use Illuminate\Http\Client\Factory;

class ExpressionService
{
    /**
     * @param string $expression
     * @return float
     */
    public static function evaluate(string $expression): float
    {
        $expression = preg_replace('/\s+/', '', $expression);

        if (!preg_match('/^[\d+\-*\/().]+$/', $expression)) {
            throw new \InvalidArgumentException('Neplatný výraz. Povoleny jsou pouze čísla, operátory +-*/ a závorky.');
        }

        return self::safeEval($expression);

//        return [
//            'to_eval' => $expression,
//            'evaluation' => self::safeEval($expression),
//            (float) self::safeEval($expression),
//        ];
    }

    /**
     * @param string $expression
     * @return float
     */
    protected static function safeEval(string $expression): float
    {
        if (preg_match('/[^0-9+\-*\/().]/', $expression)) {
            throw new \InvalidArgumentException('Neplatné znaky ve výrazu');
        }

        $result = 0;
        eval('$result = '.$expression.';');

        return (float)$result;
    }
}
