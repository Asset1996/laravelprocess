<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Providers\JSONResponseProvider;
use App\Models\User;

class AuthController extends Controller
{
    protected $jsonResponder;

    /**
     * Constructor.
     * @param JSONResponseProvider
     * @return void
     */
    public function __construct(JSONResponseProvider $jsonResponder)
    {
        $this->jsonResponder = $jsonResponder;
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request, Response $response)
    {
        echo $_ENV['MIX_APP_URL'];exit();
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

    public function usersList(JSONResponseProvider $response){
        $usersList = User::getList();
        return $this->jsonResponder->success($usersList);
    }

    public function getUsersFioAndId(JSONResponseProvider $response){
        $usersList = User::getFioAndIdList();
        return $this->jsonResponder->success($usersList);
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
            'message' => 
            'Successfully logged in.', 
            'code' => 200
        ])->withHeaders([
            'X-Auth' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
