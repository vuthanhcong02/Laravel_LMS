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
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

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

        $user = $request->user();

        if (in_array($user->role, [User::ROLE_STUDENT, User::ROLE_GUEST])) {
            $previous = url()->previous();
            $default = $user->role === User::ROLE_GUEST ? route('home') : route('student.dashboard');

            if ($request->filled('redirect_to')) {
                $default = $request->redirect_to;
            } elseif ($previous && $previous !== route('login') && $previous !== url('/login')) {
                $default = $previous;
            }

            return redirect()->intended($default);
        }

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
            $user->role = User::ROLE_GUEST;
            $user->save();

            event(new Registered($user));
            Auth::login($user);

            $previous = url()->previous();
            $default = RouteServiceProvider::HOME;
            
            if ($previous && $previous !== route('login') && $previous !== url('/login') && $previous !== route('register') && $previous !== url('/register')) {
                $default = $previous;
            }
            
            return redirect()->intended($default);
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Đăng ký thất bại. Vui lòng thử lại sau.']);
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
            return redirect()->route('home')->with('error', 'Bạn đã hủy đăng nhập ' . ucfirst($provider));
        }
        try {
            $socialUser = Socialite::driver($provider)->user();

            if (! $socialUser || ! $socialUser->getId()) {
                return redirect()->route('home')->with('error', 'Không lấy được thông tin từ ' . ucfirst($provider));
            }

            $email = $socialUser->getEmail();
            if (! $email) {
                return redirect()->route('home')->with('error', 'Tài khoản ' . ucfirst($provider) . ' không có email.');
            }

            $user = User::where('email', $email)->first();

            if ($user) {
                if ($user->provider !== $provider) {
                    return redirect()->route('home')->with('error', 'Email này đã được sử dụng. Vui lòng đăng nhập bằng mật khẩu.');
                }
            } else {
                $avatar = $socialUser->getAvatar();

                $user = User::create([
                    'first_name' => splitName($socialUser->getName())['first_name'],
                    'last_name' => splitName($socialUser->getName())['last_name'],
                    'email' => $email,
                    'password' => bcrypt(str()->random(16)),
                    'avatar' => $avatar,
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                ]);
                $user->email_verified_at = now();
                $user->role = User::ROLE_GUEST;
                $user->save();
            }

            Auth::login($user);

            if (in_array($user->role, [User::ROLE_STUDENT, User::ROLE_GUEST])) {
                $default = $user->role === User::ROLE_GUEST ? route('home') : route('student.dashboard');
                if (session()->has('social_login_redirect')) {
                    $default = session()->pull('social_login_redirect');
                }
                return redirect()->intended($default);
            }

            return redirect()->intended(RouteServiceProvider::HOME);
        } catch (InvalidStateException $e) {
            Log::warning('Social login invalid state: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Phiên đăng nhập ' . ucfirst($provider) . ' đã hết hạn hoặc không hợp lệ. Vui lòng thử lại.');
        } catch (\Exception $e) {
            Log::error('Social login error: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('home')->with('error', 'Đăng nhập ' . ucfirst($provider) . ' thất bại. Vui lòng thử lại sau.');
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
