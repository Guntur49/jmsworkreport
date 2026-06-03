<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckNmsReadOnly
{
    /**
     * Handle an incoming request.
     * 
     * Prevents NMS admin from performing write operations (create, update, delete).
     * Only allows NMS to view/read data.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is NMS admin
        if (session('user_role') === 'NMS') {
            // Reject any write operation (POST, PUT, PATCH, DELETE)
            if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                return back()->with('error', 'Admin NMS hanya memiliki akses untuk melihat data. Tidak dapat melakukan perubahan atau penghapusan data.');
            }
        }

        return $next($request);
    }
}
