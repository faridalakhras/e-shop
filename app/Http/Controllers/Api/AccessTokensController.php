<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Hash;
use App\User;


class AccessTokensController extends Controller
{
    public function store(Request $request)
    {
    $request->validate([
        'name' => ['required'],
        'password' => ['required'],
        'device_name' => ['required'],
        'abilities' => ['nullable'],
    ]);

    $user = User::where('email', $request->name)
        // ->orWhere('mobile', $request->name)
        ->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return Response::json([
            'message' => 'Invalid username and password combination',
        ], 401);
    }

    $abilities = $request->input('abilities', ['*']);
    if ($abilities && is_string($abilities)) {
        $abilities = explode(',', $abilities);
    }

    $token = $user->createToken($request->device_name);

    // $token = $user->createToken($request->device_name, $abilities, $request->ip());

        //$accessToken = $user->tokens()->latest()->first();
        $accessToken = PersonalAccessToken::findToken($token->plainTextToken);


    return Response::json([
        'token' => $token->plainTextToken,
        'user' => $user,
    ]);
    }


    public function destroy()
    {
        $user = Auth::guard('sanctum')->user();

        // Revoke (delete) all user tokens
        //$user->tokens()->delete();

        // Revoke current access token
        $user->currentAccessToken()->delete();
    }
}
