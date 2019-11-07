<?php

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

$router->group(['prefix' => 'api'], function ($router) {
    $router->get('user/getAll', 'UserController@getUsers');
    $router->get('user/{id}', 'UserController@showUser');
    $router->post('user/login', 'UserController@login');
    $router->post('user/register', 'UserController@register');
    $router->put('user/{id}', 'UserController@updateUser');

    $router->get('receipt/getAll', 'ReceiptController@getReceipts');
    $router->post('receipt/upload', 'ReceiptController@uploadReceipt');

    $router->get('activity/getAll', 'ReceiptController@getActivities');
    $router->post('activity/create', 'ReceiptController@createActivity');

    $router->get('role/getAll', 'UserController@getRoles');
    $router->post('role/create', 'UserController@createRole');

    $router->get('activity/user/{id}', 'ReceiptController@showUserActivities');
});

