<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;

class UserController extends Controller
{
    public function show($id): JsonResponse
    {
        if(Request::expectsJson()){

            app(get_class())::log([Carbon::now()->toDateTimeString(), 'USER SHOW', Request::all()]);

            $user = User::find($id);

            if(!$user){
                return $this->notFoundResponse('User not found');
            }

            return $this->successfulResponse([
                'user' => $user,
            ]);
        }
    }
}
