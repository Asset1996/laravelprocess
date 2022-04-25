<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use App\Providers\JSONResponseProvider;

class EnsureJWTIsValid extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $jsonresponder = new JSONResponseProvider;
        if ($this->auth->parser()->setRequest($request)->hasToken()) {
            try {
                $this->auth->parseToken()->authenticate();
            } catch (TokenExpiredException $e) {
                return $jsonresponder->error($e->getMessage(), 401);
            } catch(UnauthorizedHttpException $e){
                return $jsonresponder->error($e->getMessage(), 401);
            } catch(TokenInvalidException $e){
                return $jsonresponder->error($e->getMessage(), 401);
            }
        }else{
            return $jsonresponder->error('Token not provided.', 401);
        }

        return $next($request);
    }
}
