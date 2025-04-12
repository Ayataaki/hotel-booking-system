<?php
// app/Http/Middleware/CheckUserType.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $type)
    {
        if (Auth::check() && Auth::user()->typeUser === $type) {//verifie en premier si le client est authentifié et ensuite son type
            return $next($request);
        }

        abort(403);
    }
}
