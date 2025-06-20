<?php

use App\Providers\RouteServiceProvider;
use Laravel\Fortify\Features;

return [
    'guard' => 'web',
    'middleware' => ['web'],
    'auth_middleware' => 'auth',
    'passwords' => 'users',
    'username' => 'email',
    'email' => 'email',
    'views' => true,
    'home' => RouteServiceProvider::HOME,
    'prefix' => '',
    'domain' => null,
    'limiters' => [
        'login' => 'login',
        'two-factor' => 'two-factor',
    ],
    'paths' => [
        'login' => null,
        'logout' => null,
        'password.request' => null,
        'password.reset' => null,
        'password.email' => null,
        'register' => null,
        'email.verification.notice' => null,
        'email.verification.verify' => null,
        'email.verification.send' => null,
        'user.profile.show' => null,
        'user.profile.update' => null,
        'user.password.update' => null,
        'two-factor.login' => null,
        'two-factor.enable' => null,
        'two-factor.confirm' => null,
        'two-factor.disable' => null,
        'two-factor.qr-code' => null,
        'two-factor.secret-key' => null,
        'two-factor.recovery-codes' => null,
    ],
    'redirects' => [
        'login' => null,
        'logout' => null,
        'password-confirmation' => null,
        'register' => '/mypage/profile',
        'email-verification' => null,
        'password-reset' => null,
    ],
    'features' => [
        Features::registration(),
        Features::resetPasswords(),
        Features::emailVerification(),
        Features::updateProfileInformation(),
        Features::updatePasswords(),
        Features::twoFactorAuthentication([
            'confirm' => true,
            'confirmPassword' => true,
        ]),
    ],
];
