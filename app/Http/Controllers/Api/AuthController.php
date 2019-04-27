<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\SignUpClientRequest;
use App\User;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Create new user
     * @param SignUpClientRequest $req
     * @return string message
     */
    public function signUp(SignUpClientRequest $req)
    {
        User::create(request(['username', 'first_name','last_name', 'password']));
        return response()->json(['message' => 'Cet utilisateur a ete bien cree !'], 201);
    }

    /**
     * Login user and generate token and Scope by [ClientType]
     *
     * @param  LoginUserRequest $request
     * @param  Boolean remember_me
     *
     * @return token
     */
    public function logIn(LoginUserRequest $request)
    {
        $credentials = request(['username', 'password']);
        // Error !
        if (!Auth::attempt($credentials))
            return response()->json(['message' => 'Unauthorized'], 401);

        // Else
        $user = $request->user();
        // Check type and Add it like a Scope
        $clientType = $user->checkClientType();
        // Generate a Token and Specify the Scope (Admin, controller, magaziner ...)
        $tokenResult = $user->createToken('Personal Access Token', [$clientType]);
        $token = $tokenResult->token;
        // Grow up the duration of our token
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'user' => $user = $request->user(),
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    /**
     * Logout user (Revoke the token)
     * @param \Illuminate\Http\Request $request
     * @return string message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }

    /**
     * Get the authenticated User
     * @param \Illuminate\Http\Request $request
     *
     * @return [json] user object with User Infos
     */
    public function user(Request $request)
    {
        $user = $request->user();
        return response()->json($user);
    }
}
