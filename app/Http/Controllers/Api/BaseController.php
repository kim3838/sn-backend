<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;

class BaseController extends Controller
{
    public function fractalResponse(): JsonResponse
    {
        if(Request::expectsJson()){

            $this->logFilter();

            app(get_class())::log([Carbon::now()->toDateTimeString(), 'BASE REQUEST', json_decode(Request::get('filters'))]);

            return $this->successfulResponse(json_decode(Request::get('filters')));
        }
    }
}
