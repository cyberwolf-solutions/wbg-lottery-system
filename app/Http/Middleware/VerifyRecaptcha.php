<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;  // Import the Log facade
use Symfony\Component\HttpFoundation\Response;

class VerifyRecaptcha
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('Incoming request data:', $request->all());

        $recaptchaResponse = $request->input('recaptcha_token');

        if (!$recaptchaResponse) {
            Log::warning('reCAPTCHA validation failed: No recaptcha token provided.', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            return response()->json(['error' => 'reCAPTCHA validation is required.'], 422);
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => env('RECAPTCHA_SECRET_KEY'),
            'response' => $recaptchaResponse
        ]);

        $result = $response->json();

        if (!$result['success'] || $result['score'] < 0.5) {
            Log::warning('reCAPTCHA verification failed.', [
                'recaptcha_response' => $recaptchaResponse,
                'score' => $result['score'],
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            return response()->json(['error' => 'reCAPTCHA verification failed.'], 422);
        }

        Log::info('reCAPTCHA verified successfully.', [
            'score' => $result['score'],
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return $next($request);
    }
}
