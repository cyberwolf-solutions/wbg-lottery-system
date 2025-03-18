<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

        $recaptchaResponse = $request->input('recaptcha_token');

        if (!$recaptchaResponse) {
            return response()->json(['error' => 'reCAPTCHA validation is required.'], 422);
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify' , [
            'secret'   => env('RECAPTCHA_SECRET_KEY'),
            'response' => $recaptchaResponse
        ]);

        $result = $response->json();

        if (!$result['success'] || $result['score'] < 0.5) {
            return response()->json(['error' => 'reCAPTCHA verification failed.'], 422);
        }

        return $next($request);
    }
}
