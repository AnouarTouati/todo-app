<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {

        $user = User::create($request->all());
        $token = $user->createToken('token123')->plainTextToken;

        return response(json_encode(['user' => $user, 'token' => $token])
            , 201)->withHeaders([
            'Content-Type' => 'application/json',
        ]);
    }
}
