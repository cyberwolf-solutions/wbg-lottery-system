<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Schedule $schedule): void
    {
        // Vite Prefetching
        Vite::prefetch(concurrency: 3);

        // Rate Limiting for API
        RateLimiter::for('api', function ($request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });

        // Schedule the 'generate:dashboard' command to run daily at midnight
        $schedule->command('generate:dashboard')->dailyAt('00:00');

        Event::listen('kernel.handled', function ($request, $response) {
            // Handle 400 Bad Request errors
            if ($response->getStatusCode() === 400) {
                Log::debug('400 Error - Bad Request', [
                    'type' => '400_bad_request',
                    'session' => session()->all(),
                    'cookies' => $request->cookies->all(),
                    'path' => $request->path(),
                    'headers' => $request->headers->all(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
            }

            // Handle 122 errors (CURL error codes appear in content)
            if (
                $response->getStatusCode() === 200 &&
                is_string($response->getContent()) &&
                str_contains($response->getContent(), 'CURL error 122')
            ) {
                Log::debug('122 Error - CURL Request Failed', [
                    'type' => '122_curl_error',
                    'session' => session()->all(),
                    'cookies' => $request->cookies->all(),
                    'path' => $request->path(),
                    'headers' => $request->headers->all(),
                    'content' => $response->getContent(),
                    'ip' => $request->ip()
                ]);
            }
        });
        Broadcast::routes(['middleware' => ['web', 'auth:admin']]);

        require base_path('routes/channels.php');
    }
}
