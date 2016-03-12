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

Route::group(['prefix' => 'api', 'middleware' => ['api', 'jsonapi']], function () {
  Route::resource('/user', 'Askedio\Tests\App\Http\Controllers\TestController');
});
