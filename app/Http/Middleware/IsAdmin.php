<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $status): Response
    {
        // Ubah variabel dari true jadi 1 atau false jadi 0
        $status = $status == 'true' ? 1 :0;
        
        // Mengecek apakah user yang login sama dengan yang diinginkan
        // Misal true, maka jika user yang login adalah admin maka akan melanjutkan proses
        if (auth()->user()->is_admin == $status) {
            return $next($request);
        } else {
            // Jika tidak maka akan dikirimkan response 403 yang berarti tidak diperbolehkan mengakses link yang dimaksud
            return response()->json([
                'message' => 'unauthorized',
            ], 403);
        }
    }
}
