<?php

/*
|--------------------------------------------------------------------------
| Module Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for the module.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'api', 'middleware' => ['web', 'api']], function () {
  Route::resource('/user', 'Askedio\Laravel5ApiController\Tests\App\Http\Controllers\TestController');
});
