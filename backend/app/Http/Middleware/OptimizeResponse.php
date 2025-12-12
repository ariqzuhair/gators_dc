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

        // Add security and performance headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Enable HSTS for HTTPS connections
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }
        
        // Enable compression for responses > 1KB
        if (!$response->headers->has('Content-Encoding')) {
            $content = $response->getContent();
            if (strlen($content) > 1024 && function_exists('gzencode')) {
                $compressed = gzencode($content, 6);
                if ($compressed !== false) {
                    $response->setContent($compressed);
                    $response->headers->set('Content-Encoding', 'gzip');
                    $response->headers->set('Vary', 'Accept-Encoding');
                    $response->headers->set('Content-Length', strlen($compressed));
                }
            }
        }

        // Optimize cache control for API responses
        if ($request->is('api/*')) {
            if ($request->isMethod('GET')) {
                // Differentiate cache times based on endpoint
                $cacheTime = $this->getCacheTime($request->path());
                
                $response->headers->set('Cache-Control', "public, max-age={$cacheTime}, must-revalidate");
                $response->headers->set('ETag', md5($response->getContent()));
                
                // Check if client has valid cached version
                if ($request->header('If-None-Match') === $response->headers->get('ETag')) {
                    return response('', 304);
                }
            } else {
                // No cache for POST/PUT/DELETE
                $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
                $response->headers->set('Pragma', 'no-cache');
            }
        }

        return $response;
    }
    
    /**
     * Get appropriate cache time for different endpoints
     */
    private function getCacheTime(string $path): int
    {
        // Static data - cache longer
        if (str_contains($path, 'sessions') || str_contains($path, 'products')) {
            return 300; // 5 minutes
        }
        
        // User-specific data - cache shorter
        if (str_contains($path, 'user') || str_contains($path, 'profile')) {
            return 60; // 1 minute
        }
        
        // Default cache time
        return 120; // 2 minutes
    }
}
