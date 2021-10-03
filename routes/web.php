<?php

/** @var \Laravel\Lumen\Routing\Router $router */
use App\Http\Controllers\UserController;

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
$router->get('/hello', function () { return 'Hello World'; });

$router->get('/', function () use ($router) {
    return $router->app->version();
});

/*
$router->get('/fire', function () use ($router) {
    return App\Http\Controllers\FireSourceController::all();
});
*/

$router->group(['prefix' => 'fire'], function() use($router) {
    $router->get("/", "FireSourceController@index");
    $router->get("/{fire}", "FireSourceController@show");
    $router->delete("/{fire}", "FireSourceController@destroy");
    $router->post("/", "FireSourceController@store");
});

$router->group(['prefix' => 'notification'], function() use($router) {
    $router->get("/", "NotificationController@sendNotification");
});

$router->group(['prefix' => 'user'], function() use($router) {
    $router->post("/", "UserController@store");
    // $router->get("/", [UserController::class,'index']);
    $router->get("/", "UserController@index");
    $router->get("/{user}", "UserController@show");
    $router->delete("/{user}", "UserController@destroy");
});