<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OptimizeResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Add performance headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // Enable compression
        if (!$response->headers->has('Content-Encoding')) {
            $content = $response->getContent();
            if (strlen($content) > 1024 && function_exists('gzencode')) {
                $response->setContent(gzencode($content, 6));
                $response->headers->set('Content-Encoding', 'gzip');
                $response->headers->set('Vary', 'Accept-Encoding');
            }
        }

        // Cache control for API responses
        if ($request->is('api/*')) {
            // Cache GET requests for 2 minutes
            if ($request->isMethod('GET')) {
                $response->headers->set('Cache-Control', 'public, max-age=120');
            }
        }

        return $response;
    }
}
