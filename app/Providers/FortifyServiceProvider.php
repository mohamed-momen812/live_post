<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function boot()
    {
    //     // Register the user login view
    //     Fortify::loginView(function () {
    //         return view('auth.login');
    //     });

    //     // Register the user registration view
    //     Fortify::registerView(function () {
    //         return view('auth.register');
    //     });

    //     // Register the password reset view
    //     Fortify::requestPasswordResetLinkView(function () {
    //         return view('auth.forgot-password');
    //     });

    //     Fortify::resetPasswordView(function ($request) {
    //         return view('auth.reset-password', ['request' => $request]);
    //     });

    //     // Handle login
    //     Fortify::authenticateUsing(function ($request) {
    //         $user = \App\Models\User::where('email', $request->email)->first();

    //         if ($user && Hash::check($request->password, $user->password)) {
    //             return $user;
    //         }
    //     });
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
