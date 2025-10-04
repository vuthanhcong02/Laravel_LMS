<?php

namespace App\Services;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthService
{
    /**
     * Handle user login.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function handleLogin($request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Handle user logout.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function handleLogout($request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Handle user registration.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function handleRegister($request): RedirectResponse
    {
        try {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            $user->role = User::ROLE_STUDENT;
            $user->save();

            event(new Registered($user));
            Auth::login($user);

            return redirect(RouteServiceProvider::HOME);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Đăng ký thất bại: '.$e->getMessage()]);
        }
    }

    /**
     * Handle social login callback.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $provider
     */
    public function handleCallbackSocial($request, $provider): RedirectResponse
    {
        if ($request->has('error')) {
            return redirect('/login')->with('error', 'Bạn đã hủy đăng nhập '.ucfirst($provider));
        }
        try {
            $socialUser = Socialite::driver($provider)->user();

            if (! $socialUser || ! $socialUser->getId()) {
                return redirect('/login')->with('error', 'Không lấy được thông tin từ '.ucfirst($provider));
            }

            $email = $socialUser->getEmail();
            if (! $email) {
                return redirect('/login')->with('error', 'Tài khoản '.ucfirst($provider).' không có email.');
            }

            $user = User::where('email', $email)->first();

            if (! $user) {
                $user = User::create([
                    'first_name' => splitName($socialUser->getName())['first_name'],
                    'last_name' => splitName($socialUser->getName())['last_name'],
                    'email' => $email,
                    'password' => bcrypt(str()->random(16)),
                    'avatar' => $socialUser->getAvatar(),
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                ]);
                $user->email_verified_at = now();
                $user->role = User::ROLE_STUDENT;
                $user->save();
            }

            Auth::login($user);

            return redirect(RouteServiceProvider::HOME);
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Đăng nhập '.ucfirst($provider).' thất bại: '.$e->getMessage());
        }
    }

    public function resetPassword($request)
    {
        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();
                Auth::login($user);
                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
            ? redirect()->route('home')->with('status', __($status))
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }
}
