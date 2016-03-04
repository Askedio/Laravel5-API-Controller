# Laravel 5.2 API Controller
A really simple controller to help provide quick access to Modal functions and validation, mostly for a CRUD API.

Goes with https://github.com/Askedio/jQuery-Cruddy


Looking for more features? Try https://github.com/dingo/api instead. This is intended to be a really basic and easy to use API.

# Installation

### Install Package
~~~
composer require askedio/laravel5-api-controller:dev-master
~~~

### Modify your Modal, ie: app\User.php
~~~
class User extends Authenticatable
{
   
    use \Askedio\Laravel5ApiController\Traits\ApiTrait;
    use \Askedio\Laravel5ApiController\Traits\SearchableTrait;
    ...
~~~
Add the validation rules:
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
Add search rules defined from https://github.com/nicolaslopezj/searchable.
~~~
    protected $searchable = [
        'columns' => [
            'users.name' => 10,
            'users.email' => 5,
        ],
    ];
~~~

## In your Controller class
~~~
   use Askedio\Laravel5ApiController\Http\Controllers\BaseController;

   class UserController extends BaseController
   {
       public $modal = '\App\User';
       ...
~~~

Add the use and extends to enable the API controller. Define the modal you edited above.

RESTful controller functions are all setup, so we can do some routes.

## routes.php
~~~
Route::group(['prefix' => 'api', 'middleware' => ['web','api']], function()
{
  Route::resource('admin/user', 'App\Http\Controllers\UserController');
});
~~~


# Usage
## api/admin/user
#### Request Options:
* search: string
* sort: string (column)
* direction: string (asc|desc)
* limit: int (pagination limit)
* 
#### Response:
...


