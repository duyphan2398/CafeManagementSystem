<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\ApiBaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
class LoginController extends ApiBaseController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public  function login(LoginRequest $request)
    {
        $credentials = $request->only('username', 'password');

        if ( ! $token = Auth::guard("api")->attempt($credentials)) {
            return response()->json([
                'error' => 'Unauthorized',
                "true or fallse " => Auth::guard("api")->attempt($credentials)
            ], 401);
        }
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token){
        return response()->json([
            'user'          => Auth::guard("api")->user(),
            'access_token'  => $token,
            'token_type'    => 'bearer',
            'expires_in'    => Auth::guard("api")->factory()->getTTL() * 60
        ]);
    }

    public function logout(){
        Auth::guard('api')->logout();
       return response()->json([
           'message' => 'Successfully logged out'
       ],200);
    }

    /*public function refresh()
    {
        return $this->respondWithToken(
            Auth::guard('api')->refresh()
        );
    }*/

    public function user()
    {
        return response()->json([
            "users"     => User::all(),
            'message'   => 'success'
        ],200);
    }
}

