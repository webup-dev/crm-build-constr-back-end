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

    $api->group(['middleware' => 'api.auth'], function (Router $api) {
        $api->get('book', 'App\Api\V1\Controllers\BookController@index');
        $api->get('book/{id}', 'App\Api\V1\Controllers\BookController@show');
        $api->post('book', 'App\Api\V1\Controllers\BookController@store');
        $api->put('book/{id}', 'App\Api\V1\Controllers\BookController@update');
        $api->delete('book/{id}', 'App\Api\V1\Controllers\BookController@destroy');

        $api->delete('users/{id}', 'App\Api\V1\Controllers\UserController@destroy');
    });

    $api->group(['middleware' => 'api.auth'], function (Router $api) {
        $api->get('roles', 'App\Api\V1\Controllers\RolesController@index');
        $api->get('roles/{id}', 'App\Api\V1\Controllers\RolesController@show');
        $api->post('role', 'App\Api\V1\Controllers\RolesController@store');
        $api->put('roles/{id}', 'App\Api\V1\Controllers\RolesController@update');
        $api->delete('roles/{id}', 'App\Api\V1\Controllers\RolesController@destroy');
    });

    $api->group(['middleware' => 'api.auth'], function (Router $api) {
        $api->get('user-roles/full', 'App\Api\V1\Controllers\UserController@userRolesIndexFull');
        $api->get('user-roles', 'App\Api\V1\Controllers\UserController@userRolesIndex');
        $api->get('user-roles/{id}', 'App\Api\V1\Controllers\UserController@specifiedUserRolesIndex');
        $api->post('user-roles/{id}', 'App\Api\V1\Controllers\UserController@userRolesStore');
        $api->put('user-roles/{id}', 'App\Api\V1\Controllers\UserController@userRolesUpdate');
        $api->delete('user-roles/{id}', 'App\Api\V1\Controllers\UserController@userRolesDestroy');

        $api->get('profiles/{id}', 'App\Api\V1\Controllers\ProfilesController@getUser');
    });

    $api->get('book', 'App\Api\V1\Controllers\BookController@index');

    $api->get('hello', function() {
        return response()->json([
            'message' => 'This is a simple example of item returned by your APIs. Everyone can see it.'
        ]);
    });
});


