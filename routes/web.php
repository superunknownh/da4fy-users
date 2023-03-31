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

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('users',  ['middleware' => ['auth', 'onlysuperuser'], 'uses' => 'UserController@list']);
    $router->get('users/iam', ['middleware' => ['auth'], 'uses' => 'UserController@iam']);
    $router->get('users/{id}', ['middleware' => ['auth'], 'uses' => 'UserController@findById']);
    $router->post('users', ['middleware' => ['auth', 'onlysuperuser'], 'uses' => 'UserController@create']);
});
