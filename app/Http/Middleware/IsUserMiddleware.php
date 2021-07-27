<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IsUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (User::find($request->payer_id)?->isShopkeeper()) {

            return response()->json([
                                        'message' => __('You are not allowed to access this route')
                                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $next($request);
    }
}
