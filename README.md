# Laravel 5.2 API Controller
A simple controller to help provide quick access to Modal functions and validation, mostly for a CRUD API.

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

## In your Controller class
~~~
use Askedio\Laravel5ApiController\Http\Controllers\BaseController;

class UserController extends BaseController
{
    public $modal = '\App\User';
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
