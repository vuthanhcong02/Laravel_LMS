<?php

namespace App\Providers;

use App\Models\User;
use App\Repositories\User\IUserRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Pulse\Facades\Pulse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IUserRepository::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
            URL::forceScheme('https');
            request()->server->set('HTTPS', 'on');
        }

        // Configure custom user resolver for Laravel Pulse
        Pulse::users(function ($ids) {
            return User::findMany($ids)->map(fn (User $user) => [
                'id'     => $user->id,
                'name'   => trim($user->first_name . ' ' . $user->last_name) ?: $user->email,
                'extra'  => $user->email,
                'avatar' => $user->avatar_url,
            ]);
        });
    }
}
