<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use App\Providers\JSONResponseProvider;

class AuthController extends Controller
{
    /**
     * Constructor.
     * 
     * @return void
     */
    public function __construct(JSONResponseProvider $jsonResponder)
    {
        $this->jsonResponder = $jsonResponder;
        $this->middleware('isTokenValid', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Response $response)
    {
        $credentials = request(['iin', 'password']);
        if (! $token = Auth::attempt($credentials)) {
    
            return $this->jsonResponder->error('User not found');
        }
        return $this->respondWithToken($token, $response);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json([
            'result' => True,
            'message' => 'Successfully logged out',
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken(string $token, Response $response): Response
    {
        return $response->setContent([
            'result' => True, 
            'message' => 'Successfully logged in.', 
            'code' => 200
        ])->withHeaders([
            'X-Auth' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
