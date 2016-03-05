[![Build Status](https://img.shields.io/travis/Askedio/Laravel5-API-Controller/master.svg?style=flat-square)](https://travis-ci.org/Askedio/Laravel5-API-Controller)
[![StyleCI](https://styleci.io/repos/52752552/shield)](https://styleci.io/repos/52752552)


# Laravel 5.2 API Controller
A really simple package that provides an API for CRUD related tasks based on Modals and Resource Controllers.

Made for [jQuery CRUDdy](https://github.com/Askedio/jQuery-Cruddy) but can work with anything.


# Installation
Some better examples are in the [wiki](https://github.com/Askedio/Laravel5-API-Controller/wiki).

### Install Package
~~~
composer require askedio/laravel5-api-controller:dev-master
~~~

### Modal, ie: app\User.php
##### Add the Traits
~~~
class User extends Authenticatable
{
   
    use \Askedio\Laravel5ApiController\Traits\ApiTrait;
    use \Askedio\Laravel5ApiController\Traits\SearchableTrait;
    ...
~~~
##### Add the validation rules
~~~
    protected $rules = [
      'update' => [
         ...
      ],
      'create' => [
         ...
      ],
    ];
~~~
##### Add search rules defined from https://github.com/nicolaslopezj/searchable 
~~~
    protected $searchable = [
        'columns' => [
            'users.name' => 10,
            'users.email' => 5,
        ],
    ];
~~~

## Controller, ie: app\Http\Controllers\Api\UserController.php
* Add the use
* Modify the extends
* Define $modal
~~~
   use Askedio\Laravel5ApiController\Http\Controllers\BaseController;

   class UserController extends BaseController
   {
       public $modal = '\App\User';
       ...
~~~

## Routes, ie: app/Http/routes.php
~~~
Route::group(['prefix' => 'api', 'middleware' => ['web','api']], function()
{
  Route::resource('admin/user', 'Api\UserController');
});
~~~


# Usage
* Laravels Resource Routes are being used.
* Access the route like you've defined, ie: /api/admin/user/.
* Perform RESTful acts on the Resource Controller
** POST/PATCH accepts the fillable values
** The 'fillable' data in your Model is what you can POST or PATCH with.

##### POST
* success: Returns the Models results.
* validation failure: Returns errors[[field => '', error => '']]
* failure: Returns 500

##### GET
* success: Returns the Models paginate() results.
* failure: Returns 404

##### PATCH
* success: Returns the Models results.
* validation failure: Returns errors[[field, error]]
* failure: Returns 500

##### DELETE
* success: Returns the Models results.
* failure: Returns 500

# Results
Results are sent in an array.
~~~
// Errors:
['success' => false, 'errors' => []]

// No errors:
['success' => true, 'results' => []];
~~~


# Notices
I've been looking at couple other similar packages:
* https://github.com/nilportugues/laravel5-jsonapi
* https://github.com/dingo/api

Nilportugues' package is pretty close to what I was after minus create and search. 

Dingo is overly complicated, I wanted something simple. It took me ~2mins to unit test and live test this package, Ding or even Nils package seem like they require a bit more integration, so more time.

Both packages offer 'better' support for the JSON API format where I am simply giving you Laravels output. I am not overly concered about this but it would be nice to implement in the future.

# Comments
This is package is open to code review and comments, please let me know if I have made mistakes, I love feedback.

You can reach me here or on twitter, @askedio.

Thank you.
