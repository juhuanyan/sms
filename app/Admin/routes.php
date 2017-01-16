<?php

use Illuminate\Routing\Router;

Route::group([
    'prefix'        => config('admin.prefix'),
    'namespace'     => Admin::controllerNamespace(),
    'middleware'    => ['web', 'admin'],
], function (Router $router) {

    $router->get('/', 'HomeController@index');

});

Route::group([
    'prefix'        => config('admin.prefix'),
    'namespace'     => Admin::controllerNamespace(),
    'middleware'    => ['web', 'admin', 'check.ip'],
], function (Router $router) {

    $router->resource('jiekou', 'JiekouController');
    $router->resource('user', 'UserController');
    $router->resource('sms', 'SmsController');

});
