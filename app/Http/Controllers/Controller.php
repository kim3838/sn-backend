<?php

namespace App\Http\Controllers;

use App\Traits\FractalTransformer;
use App\Traits\ResponsesJson;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class Controller extends BaseController
{
    use AuthorizesRequests,
        ValidatesRequests,
        ResponsesJson,
        FractalTransformer;

    public static function __callStatic(string $method, array $parameters)
    {
        return call_user_func_array(self::$methods[$method], $parameters);
    }

    public function log($log)
    {
        Log::debug(print_r($log, true));
    }

    public function logFilter()
    {
        Log::debug(print_r(Request::all(), true));

        if (is_string(Request::get('filters'))) {
            logger('FILTER IS STRING');
            Log::debug(print_r(json_decode(Request::get('filters')), true));
        }

        if (is_array(Request::get('filters'))) {
            logger('FILTER IS ARRAY');
            Log::debug(print_r(Request::get('filters'), true));
        }
    }
}
