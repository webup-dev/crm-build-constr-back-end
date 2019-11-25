<?php

namespace App\Http;

use Barryvdh\Cors\HandleCors;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Tymon\JWTAuth\Http\Middleware\RefreshToken;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        HandleCors::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'                                          => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic'                                    => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings'                                      => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can'                                           => \Illuminate\Auth\Middleware\Authorize::class,
        'guest'                                         => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle'                                      => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'jwt.auth'                                      => \Tymon\JWTAuth\Http\Middleware\Authenticate::class,
        'jwt.refresh'                                   => RefreshToken::class,
        'activity'                                      => \App\Http\Middleware\Activity::class,
        'platform.developer'                            => \App\Http\Middleware\Developer::class,
        'platform.superadmin'                           => \App\Http\Middleware\PlatformSuperadmin::class,
        'platform.admin'                                => \App\Http\Middleware\PlatformAdmin::class,
        'organization.superadmin'                       => \App\Http\Middleware\OrganizationSuperadmin::class,
        'organizations_organization.admin'              => \App\Http\Middleware\Organizations_OrganizationAdmin::class,
        'user_profiles_organization.admin'              => \App\Http\Middleware\UserProfiles_OrganizationAdmin::class,
        'user_profiles_organization.admin.own'          => \App\Http\Middleware\UserProfiles_OrganizationAdminOwn::class,
        'customers_organization.admin'                  => \App\Http\Middleware\Customers_OrganizationAdmin::class,
        'customers_organization.superadmin'             => \App\Http\Middleware\Customers_OrganizationSuperadmin::class,
        'customers_organization.users'                  => \App\Http\Middleware\Customers_OrganizationUsers::class,
        'customers_organization.admin.own'              => \App\Http\Middleware\Customers_OrganizationAdminOwn::class,
        'organization.user'                             => \App\Http\Middleware\OrganizationUser::class,
        'soft_deleted_menu_organization.admin'          => \App\Http\Middleware\SoftDeletedMenu_OrganizationAdmin::class,
        'customer_comments_organization.users.customer' => \App\Http\Middleware\CustomerComments_OrganizationUsersAndCustomer::class,
        'customer_comments_author'                      => \App\Http\Middleware\CustomerComments_Author::class,
    ];
}
