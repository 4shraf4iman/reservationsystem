<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    // app/Providers/RouteServiceProvider.php

    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

        // block for email verification redirection
        Route::get('/email/verify/{id}/{hash}', function (Request $request) {
            $user = \App\Models\User::findOrFail($request->route('id'));

            if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
                throw new \Illuminate\Auth\Access\AuthorizationException;
            }

            if ($user->hasVerifiedEmail()) {
                return redirect(static::HOME);
            }

            if ($user->markEmailAsVerified()) {
                event(new \Illuminate\Auth\Events\Verified($user));
            }

            return redirect(static::HOME)->with('verified', true);
        })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    }


    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
