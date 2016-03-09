[![Build Status](https://img.shields.io/travis/Askedio/Laravel5-API-Controller/master.svg?style=flat-square)](https://travis-ci.org/Askedio/Laravel5-API-Controller)
[![StyleCI](https://styleci.io/repos/52752552/shield)](https://styleci.io/repos/52752552)


# Laravel 5.2 API Controller
A really simple package that provides an API for CRUD related tasks based on Modals and Resource Controllers.

Made for [jQuery CRUDdy](https://github.com/Askedio/jQuery-Cruddy) but can work with anything. [Live Demo](https://cruddy.io/app/)


# Installation
Some better examples are in the [wiki](https://github.com/Askedio/Laravel5-API-Controller/wiki).

### Install Package
~~~
composer require askedio/laravel5-api-controller:dev-master
~~~
### Add to providers
~~~
    'providers' => [
        Askedio\Laravel5ApiController\Providers\GenericServiceProvider::class,
        ...
~~~

### Modal, ie: app\User.php
Add the Traits
~~~
class User extends Authenticatable
{
   
    use \Askedio\Laravel5ApiController\Traits\ApiTrait;
    use \Askedio\Laravel5ApiController\Traits\SearchableTrait;
    ...
~~~
Add the validation rules [optional]
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
Add search rules defined from https://github.com/nicolaslopezj/searchable  [optional]
~~~
    protected $searchable = [
        'columns' => [
            'users.name' => 10,
            'users.email' => 5,
        ],
    ];
~~~

Add the $id_field  [optional]
~~~
    protected $id_field = 'id';
~~~
Transform data [optional]
~~~
    public function transform(User $user) {
        return [
            'name' => $user->name,
            'email' => $user->email,
        ];
    }
~~~
#### Relations
Relations can be added as normal, the modal name will be used as the 'include' value.

## Controller, ie: app\Http\Controllers\Api\UserController.php
~~~
   use Askedio\Laravel5ApiController\Http\Controllers\BaseController;

   class UserController extends BaseController
   {
       public $modal = '\App\User';
   }
~~~

## Routes, ie: app/Http/routes.php
The jsonapi middleware will deny requests without proper Accept and Content-Type.
~~~
Route::group(['prefix' => 'api', 'middleware' => ['api', 'jsonapi']], function()
{
  Route::resource('admin/user', 'Api\UserController');
});
~~~


# Usage
* Laravels Resource Routes are being used.
* Access the route like you've defined, ie: /api/admin/user/[id].


##### POST [/]
* accepts: 'fillable' data in your Model.
* validation failure: Returns 403
* failure: Returns 500

##### GET [/id|/]
* failure: Returns 404

##### PATCH [/id]
* accepts: 'fillable' data in your Model.
* failure: Returns 500

##### DELETE [/id]
* failure: Returns 500

### JSON API methods can also be used.

~~~
/api/admin/user/157?include=profiles&fields[user]=name,id&fields[profiles]=id,phone
/api/admin/user?page=1&sort=id&limit=10
/api/admin/user?page=1&sort=id&limit=10&sort=-id,name&include=profiles&fields[user]=name,id&fields[profiles]=id,phone
~~~



# Similar Packages
* https://github.com/nilportugues/laravel5-jsonapi
* https://github.com/dingo/api



# Comments
This is package is open to code review and comments, please let me know if I have made mistakes, I love feedback.

You can reach me here or on twitter, @askedio.

Thank you.
