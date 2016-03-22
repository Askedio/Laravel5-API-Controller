![laravel-cruddy](http://i.imgur.com/TmEh1m6.jpgg)

A really simple package that provides a CRUD JSON API for your Laravel 5 application.

[![Build Status](https://travis-ci.org/Askedio/laravel-Cruddy.svg?branch=master)](https://travis-ci.org/Askedio/laravel-Cruddy)
[![StyleCI](https://styleci.io/repos/52752552/shield)](https://styleci.io/repos/52752552)
[![Code Climate](https://codeclimate.com/github/Askedio/laravel-Cruddy/badges/gpa.svg)](https://codeclimate.com/github/Askedio/laravel-Cruddy)
[![Codacy Badge](https://api.codacy.com/project/badge/grade/c2f2291fe3af4ea3a511afa64ddc034b)](https://www.codacy.com/app/gcphost/laravel-Cruddy)
[![Codacy Badge](https://api.codacy.com/project/badge/coverage/c2f2291fe3af4ea3a511afa64ddc034b)](https://www.codacy.com/app/gcphost/laravel-Cruddy)

* [Live Demo](https://cruddy.io/app/).
* [Laravel 5.2 Example Package](https://github.com/Askedio/Laravel-5-CRUD-Example).
* Plays well with [jQuery CRUDdy](https://github.com/Askedio/jQuery-Cruddy).



# Installation
### Composer: require
~~~
composer require askedio/laravel-cruddy:dev-master
~~~


### Providers: config/app.php
Add the Service Provider to your providers array.
~~~
'providers' => [
    Askedio\Laravel5ApiController\Providers\GenericServiceProvider::class,
        ...
~~~




### Model: app/User.php
Add the traits to your Model to enable the Api and Search features. [More Details & Options.](https://github.com/Askedio/laravel-Cruddy/wiki/Models)
~~~
class User extends Authenticatable
{

    use \Askedio\Laravel5ApiController\Traits\ModelTrait;
    use \Askedio\Laravel5ApiController\Traits\SearchableTrait;
    ...
~~~




### Controller: app/Http/Controllers/Api/UserController.php
Create a new controller for your API. [More Details & Options](https://github.com/Askedio/laravel-Cruddy/wiki/Controllers).
~~~
<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller as BaseController;

class UserController extends BaseController
{
    use \Askedio\Laravel5ApiController\Traits\ControllerTrait;
    public $modal = \App\User::class;
}
~~~

### Routes: app/Http/routes.php
Create a prefixed group for your api and assign the api and jsonapi middleware. [More Details & Options](https://github.com/Askedio/laravel-Cruddy/wiki/Routes).
~~~
Route::group(['prefix' => 'api', 'middleware' => ['api', 'jsonapi']], function()
{
  Route::resource('user', 'Api\UserController');
});
~~~

<br />
<br />



# Usage
Consume the API using Laravels resource routes, GET, PATCH, POST and DELETE. [More Details & Options](https://github.com/Askedio/laravel-Cruddy/wiki/Usage).

### Example
~~~
GET /api/user/1
~~~

~~~
HTTP/1.1 200 OK
Content-Type: application/vnd.api+json

{
  "data": {
    "type": "users",
    "id": 1,
    "attributes": {
      "id": 1,
      "name": "Test User",
      "email": "test@test.com"
    }
  },
  "links": {
    "self": "/api/user/1"
  },
  "jsonapi": {
    "version": "1.0",
    "self": "v1"
  }
}
~~~


<br /><br />


# Comments
My goal is a plug-n-play json api for Laravel. You shouldn't need to configure much of anything to enable the api on your models but if you still want advanced features like relations, searching, etc, you get that too.

If you have any comments, opinions or can code review please reach me here or on twitter, [@asked_io](https://twitter.com/asked_io). You can also follow me on my website, [asked.io](https://asked.io).


Thank you.

-William
