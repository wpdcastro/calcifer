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

$router->get('/fire', function () use ($router) {
    return \App\Fire::all();
});

$router->group(['prefix' => 'fire'], function() use($router) {
    $router->get("/", "FireController@index");
    $router->get("/{fire}", "FireController@show");

    $router->delete("/{fire}", "FireController@destroy");

    $router->post("/", "FireController@store");
});

$router->group(['prefix' => 'notification'], function() use($route) {
    $route->get("/", "FireController@index");
    $route->post("/", "FireController@store");
    $route->get("/{fire}", "FireController@show");
    $route->delete("/{fire}", "FireController@destroy");
    $route->put("/{fire}", "FireController@update");
});