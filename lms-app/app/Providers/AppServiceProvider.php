<?php

namespace App\Providers;

use App\Models\User;
use App\Repositories\User\IUserRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Log;
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
            request()->server->set('SERVER_PORT', 443);
        }

        // Safety net: đảm bảo cache directory tồn tại khi dùng file driver.
        // Phòng khi `php artisan cache:clear` xóa mất thư mục con, gây lỗi
        // file_put_contents() "No such file or directory" trên production.
        if (config('cache.default') === 'file') {
            $cachePath = config('cache.stores.file.path');
            if ($cachePath && ! is_dir($cachePath)) {
                if (! @mkdir($cachePath, 0755, true) && ! is_dir($cachePath)) {
                    Log::error('[AppServiceProvider] Không thể tạo cache directory: ' . $cachePath);
                }
            }
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
