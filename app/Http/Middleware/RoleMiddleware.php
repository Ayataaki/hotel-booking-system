<?php
// app/Http/Middleware/CheckUserType.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $type)
    {
        // ici il y avait une faute : typeUser au lieu de userType.
        if (Auth::check() && Auth::user()->userType === $type) {//verifie en premier si le client est authentifié et ensuite son type
            return $next($request);
        }

        abort(403);
    }
}
