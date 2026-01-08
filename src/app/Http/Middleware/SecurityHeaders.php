<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Content Security Policy (CSP)
        // 段階的に厳格化できるよう、まずは基本的な設定
        $csp = "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://code.jquery.com; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:; connect-src 'self'; frame-ancestors 'none';";
        $response->headers->set('Content-Security-Policy', $csp);

        // X-Frame-Options: クリックジャッキング対策
        $response->headers->set('X-Frame-Options', 'DENY');

        // X-Content-Type-Options: MIMEタイプスニッフィング対策
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // X-XSS-Protection (レガシーブラウザ用)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer-Policy: リファラー情報の制御
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions-Policy: ブラウザ機能の制御
        //$response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        return $response;
    }
}

