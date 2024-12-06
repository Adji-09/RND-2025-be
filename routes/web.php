<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router)
{
    // LOGIN
    $router->post('login', 'AuthController@login');
    $router->post('login_face', 'AuthController@login_face');

    // PROFILE
    $router->get('profile/{id}', 'ProfileController@profile');
    $router->post('change_profile', 'ProfileController@change_profile');
    $router->post('check_password', 'ProfileController@check_password');
    $router->post('change_password', 'ProfileController@change_password');
    $router->post('face_enroll', 'ProfileController@face_enroll');
    $router->get('list_face/{id}', 'ProfileController@list_face');
    $router->delete('remove_face/{id}', 'ProfileController@remove_face');

    // USER
    $router->get('user/getAll', 'UserController@getAll');
    $router->get('user/getById/{id}', 'UserController@getById');
    $router->post('user/getByUsername', 'UserController@getByUsername');
    $router->post('user/getByEmail', 'UserController@getByEmail');
    $router->get('user/getRoleByStatus', 'UserController@getRoleByStatus');
    $router->post('user/store', 'UserController@store');
    $router->post('user/update', 'UserController@update');
    $router->delete('user/destroy/{id}', 'UserController@destroy');

    // MODULE
    $router->get('module/getAll', 'ModuleController@getAll');
    $router->get('module/getById/{id}', 'ModuleController@getById');
    $router->post('module/update', 'ModuleController@update');
    $router->post('module/update_pos', 'ModuleController@update_pos');

    // THEME
    $router->get('theme/getByUserId/{id}', 'ThemeController@getByUserId');
    $router->post('theme/update', 'ThemeController@update');

    // LOGOUT
    $router->post('logout', 'AuthController@logout');
});
