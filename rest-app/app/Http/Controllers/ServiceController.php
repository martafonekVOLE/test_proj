<?php

namespace App\Http\Controllers;

use App\Services\AirportService;
use App\Services\ExpressionService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;

class ServiceController extends Controller
{
    private const ATTR_AIRPORT_QUERY = 'queryAirportTemp';

    private const ATTR_STOCK_QUERY = 'queryStockPrice';

    private const ATTR_EVAL_QUERY = 'queryEval';

    /**
     * @param \Illuminate\Validation\Factory $validationFactory
     */
    public function __construct(
        private readonly Factory $validationFactory,
    ){
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Services\AirportService $airportService
     * @param \App\Services\StockService $stockService
     * @param \App\Services\ExpressionService $expressionService
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Http\Client\ConnectionException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function service(Request $request, AirportService $airportService, StockService $stockService, ExpressionService $expressionService): Response
    {

        // If none of supported query parameters were found, return error
        if(!$request->hasAny([self::ATTR_AIRPORT_QUERY, self::ATTR_STOCK_QUERY, self::ATTR_EVAL_QUERY])) {
            return response('Invalid request', 400);
        }

        $this->validate($request);

        $airport = $request->input(self::ATTR_AIRPORT_QUERY);
        if($airport !== null) {
            // Now airport exists and has been validated
            return response($airportService->getAirportTemperature($airport), 200);
        }

        $stock = $request->input(self::ATTR_STOCK_QUERY);
        if($stock !== null) {
            return response($stockService->getStockPrice($stock), 200);
        }

        $eval = $request->input(self::ATTR_EVAL_QUERY);
        if($eval !== null) {
            return response($expressionService->evaluate($eval), 200);
        }

        return response('', 200);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validate(Request $request): void
    {
        $validator = $this->validationFactory->make($request->all(), [
            self::ATTR_AIRPORT_QUERY => 'string|size:3|alpha',
            self::ATTR_STOCK_QUERY => 'string|min:1|max:5|regex:/^[A-Z0-9]+$/',
            self::ATTR_EVAL_QUERY => 'string|regex:/^[\d\s\+\-\*\/\(\)\.]+$/',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
