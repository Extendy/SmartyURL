<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array<string, string>
     * @phpstan-var array<string, class-string>
     */
    public array $aliases = [
        'csrf'            => CSRF::class,
        'toolbar'         => DebugToolbar::class,
        'honeypot'        => Honeypot::class,
        'invalidchars'    => InvalidChars::class,
        'secureheaders'   => SecureHeaders::class,
        'localization'    => \App\Filters\Localization::class,
        'afterlangchange' => \App\Filters\LangFilter::class,  // it is just a test just to show how filter works , mshannaq not real filter

        // for shiled .. already used and I put it here just to see it
        // already loaded for you by the registrar class located at
        // src/Config/Registrar.php.
        /*
        'session'     => \CodeIgniter\Shield\Filters\SessionAuth::class,
        'tokens'      => \CodeIgniter\Shield\Filters\TokenAuth::class,
        'chain'       => \CodeIgniter\Shield\Filters\ChainAuth::class,
        'auth-rates'  => \CodeIgniter\Shield\Filters\AuthRates::class,
        'group'       => \CodeIgniter\Shield\Filters\GroupFilter::class,
        'permission'  => \CodeIgniter\Shield\Filters\PermissionFilter::class,
        'force-reset' => \CodeIgniter\Shield\Filters\ForcePasswordResetFilter::class,
        'jwt'         => \CodeIgniter\Shield\Filters\JWTAuth::class,
        */
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array<string, array<string, array<string, string>>>|array<string, array<string>>
     * @phpstan-var array<string, list<string>>|array<string, array<string, array<string, string>>>
     */
    public array $globals = [
        'before' => [
            // 'honeypot',
            // 'csrf',
            'csrf' => ['except' => ['api/*']],
            // localization filter to detect the lang layout
            'localization',
            // @TODO remove tests* from before production
            'session' => ['except' => ['/', 'go/*', 'tests*', 'lang*', 'account/login*', 'account/register', 'account/auth/a/*']],
            // 'invalidchars',
        ],
        'after' => [
            'toolbar',
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'post' => ['foo', 'bar']
     *
     * If you use this, you should disable auto-routing because auto-routing
     * permits any HTTP method to access a controller. Accessing the controller
     * with a method you don't expect could bypass the filter.
     */
    public array $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     */
    public array $filters = [
        'auth-rates' => [
            'before' => [
                'account/login*', 'account/register', 'auth/*',
            ],
        ],
    ];
}
