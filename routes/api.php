<?php

use Dingo\Api\Routing\Router;
use Illuminate\Http\Request;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    $api->group(['prefix' => 'auth'], function(Router $api) {
        $api->post('signup', 'App\Api\V1\Controllers\SignUpController@signUp');
        $api->post('login', 'App\Api\V1\Controllers\LoginController@login');

        $api->post('recovery', 'App\Api\V1\Controllers\ForgotPasswordController@sendResetEmail');
        $api->post('reset', 'App\Api\V1\Controllers\ResetPasswordController@resetPassword');

        $api->post('logout', 'App\Api\V1\Controllers\LogoutController@logout');
        $api->post('refresh', 'App\Api\V1\Controllers\RefreshController@refresh');
        $api->get('me', 'App\Api\V1\Controllers\UserController@me');
    });

//    $api->post('auth/logout', 'App\Api\V1\Controllers\LogoutController@logout');

    $api->group(['middleware' => 'jwt.auth'], function(Router $api) {
        $api->get('protected', function() {
            return response()->json([
                'message' => 'Access to protected resources granted! You are seeing this text as you provided the token correctly.'
            ]);
        });

        $api->get('refresh', [
            'middleware' => 'jwt.refresh',
            function() {
                return response()->json([
                    'message' => 'By accessing this endpoint, you can refresh your access token at each request. Check out this response headers!'
                ]);
            }
        ]);
    });

    $api->group(['middleware' => ['api.auth','activity']], function (Router $api) {
        $api->get('book', 'App\Api\V1\Controllers\BookController@index');
        $api->get('book/{id}', 'App\Api\V1\Controllers\BookController@show');
        $api->post('book', 'App\Api\V1\Controllers\BookController@store');
        $api->put('book/{id}', 'App\Api\V1\Controllers\BookController@update');
        $api->delete('book/{id}', 'App\Api\V1\Controllers\BookController@destroy');

        $api->delete('users/{id}', 'App\Api\V1\Controllers\UserController@destroy');
    });

    $api->group(['middleware' => ['api.auth','platform.superadmin','activity']], function (Router $api) {
        $api->get('roles', 'App\Api\V1\Controllers\RolesController@index');
        $api->get('roles/{id}', 'App\Api\V1\Controllers\RolesController@show');
        $api->post('role', 'App\Api\V1\Controllers\RolesController@store');
        $api->put('roles/{id}', 'App\Api\V1\Controllers\RolesController@update');
        $api->delete('roles/{id}', 'App\Api\V1\Controllers\RolesController@destroy');
    });

    $api->group(['middleware' => ['api.auth','platform.superadmin','activity']], function (Router $api) {
        $api->get('controllers', 'App\Api\V1\Controllers\VcontrollersController@index');
        $api->get('controllers/{id}', 'App\Api\V1\Controllers\VcontrollersController@show');
        $api->post('controllers', 'App\Api\V1\Controllers\VcontrollersController@store');
        $api->put('controllers/{id}', 'App\Api\V1\Controllers\VcontrollersController@update');
        $api->delete('controllers/{id}', 'App\Api\V1\Controllers\VcontrollersController@destroy');
    });

    $api->group(['middleware' => ['api.auth','activity']], function (Router $api) {
        $api->get('methods/{id}', 'App\Api\V1\Controllers\MethodsController@index');
        $api->get('methods/{id}/show', 'App\Api\V1\Controllers\MethodsController@show');
        $api->post('methods/{id}', 'App\Api\V1\Controllers\MethodsController@store');
        $api->put('methods/{id}', 'App\Api\V1\Controllers\MethodsController@update');
        $api->delete('methods/{id}', 'App\Api\V1\Controllers\MethodsController@destroy');
    });

    $api->group(['middleware' => ['api.auth', 'activity']], function (Router $api) {
        $api->get('rules', 'App\Api\V1\Controllers\RulesController@getRules');
        $api->get('rules/main-role', 'App\Api\V1\Controllers\RulesController@getMainRole');
    });

    $api->group(['middleware' => 'api.auth'], function (Router $api) {
        $api->get('action-roles', 'App\Api\V1\Controllers\ActionRolesController@index');
        $api->get('action-roles/{id}', 'App\Api\V1\Controllers\ActionRolesController@show');
        $api->post('action-roles', 'App\Api\V1\Controllers\ActionRolesController@store');
        $api->put('action-roles/{id}', 'App\Api\V1\Controllers\ActionRolesController@update');
        $api->delete('action-roles/{id}', 'App\Api\V1\Controllers\ActionRolesController@destroy');
    });

    $api->group(['middleware' => ['api.auth', 'platform.superadmin', 'activity']], function (Router $api) {
        $api->get('user-roles/full', 'App\Api\V1\Controllers\UserController@userRolesIndexFull');
        $api->get('user-roles', 'App\Api\V1\Controllers\UserController@userRolesIndex');
        $api->get('user-roles/{id}', 'App\Api\V1\Controllers\UserController@specifiedUserRolesIndex');
        $api->post('user-roles/{id}', 'App\Api\V1\Controllers\UserController@userRolesStore');
        $api->put('user-roles/{id}', 'App\Api\V1\Controllers\UserController@userRolesUpdate');
        $api->delete('user-roles/{id}', 'App\Api\V1\Controllers\UserController@userRolesDestroy');

        $api->get('profiles/{id}', 'App\Api\V1\Controllers\ProfilesController@getUser');
    });

    $api->group(['middleware' => ['api.auth','activity']], function (Router $api) {
        $api->get('method-role/{id}', 'App\Api\V1\Controllers\MethodRolesController@show');
        $api->get('method-roles/{id}', 'App\Api\V1\Controllers\MethodRolesController@getRoles');
        $api->post('method-roles/{id}', 'App\Api\V1\Controllers\MethodRolesController@store');
        $api->put('method-roles/{id}', 'App\Api\V1\Controllers\MethodRolesController@update');
        $api->delete('method-role/{id}', 'App\Api\V1\Controllers\MethodRolesController@destroy');
        $api->delete('method-roles/{id}', 'App\Api\V1\Controllers\MethodRolesController@destroyRoles');
    });

    $api->group(['middleware' => ['api.auth', 'platform.superadmin','activity']], function (Router $api) {
        $api->get('organizations', 'App\Api\V1\Controllers\OrganizationsController@index');
        $api->get('organizations/{id}', 'App\Api\V1\Controllers\OrganizationsController@show');
        $api->post('organizations', 'App\Api\V1\Controllers\OrganizationsController@store');
        $api->put('organizations/{id}', 'App\Api\V1\Controllers\OrganizationsController@update');
        $api->delete('organizations/{id}', 'App\Api\V1\Controllers\OrganizationsController@destroy');
    });

    $api->group(['middleware' => ['api.auth', 'platform.superadmin', 'activity']], function (Router $api) {
        $api->get('activities', 'App\Api\V1\Controllers\ActivitiesController@index');
        $api->delete('activities', 'App\Api\V1\Controllers\ActivitiesController@destroy');
    });

//    $api->get('book', 'App\Api\V1\Controllers\BookController@index');

    $api->get('hello', function() {
        return response()->json([
            'message' => 'This is a simple example of item returned by your APIs. Everyone can see it.'
        ]);
    });
});


