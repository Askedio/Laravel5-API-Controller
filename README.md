# Laravel 5.2 API Controller
A really simple package that provides an API for CRUD related tasks based on Modals and Resource Controllers.

Works with https://github.com/Askedio/jQuery-Cruddy


# Installation

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

## Controller, ie: app\Http\Controllers\UserController.php
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
  Route::resource('admin/user', 'App\Http\Controllers\UserController');
});
~~~


# Usage
* Laravels Resource Routes are being used.
* Access the route like you've defined, ie: /api/admin/user/.
* Perform RESTful acts on the Resource Controller
** The 'fillable' data is what you can POST or PATCH with.

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


# Customization
Check the wiki.
https://github.com/Askedio/Laravel5-API-Controller/wiki
