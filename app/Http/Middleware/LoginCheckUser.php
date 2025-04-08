<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginCheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('loginCheckUser') && !session()->has('loggedInInventoryAdmin')) {
            if ($request->path() != '/') {
                return redirect('/');
            }
        } else {
            if (strpos($request->path(), '/user/') === 0) {
                return back();
            }
        }
        
        return $next($request);        
    }
}
