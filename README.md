[![Build Status](https://img.shields.io/travis/Askedio/Laravel5-API-Controller/master.svg?style=flat-square)](https://travis-ci.org/Askedio/Laravel5-API-Controller)
[![StyleCI](https://styleci.io/repos/52752552/shield)](https://styleci.io/repos/52752552)


# Laravel 5.2 API Controller
A really simple package that provides a JSON API for CRUD related tasks.

Made for [jQuery CRUDdy](https://github.com/Askedio/jQuery-Cruddy) but can work with anything. 

* [Live Demo](https://cruddy.io/app/) 
* [Laravel Demo](https://github.com/Askedio/Laravel-5-CRUD-Example)


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


# JSON API Spec
There is still some work to-do on the json api spec.

* Data sent from the server is (from what I can tell) is JSON API spec.
* Data sent to the server (right now) is a json array, not JSON API spec, but just {name: value}.
* Headers are validated with the jsonapi middleware
* Request data is validated, put has a whitelist, values are based upon the modal.
* Errors are being transitioned to exceptions, ones that are have proper error reporting with source.




# Similar Packages
* https://github.com/nilportugues/laravel5-jsonapi
* https://github.com/dingo/api



# Comments
This is package is open to code review and comments, please let me know if I have made mistakes, I love feedback.

You can reach me here or on twitter, @askedio.

Thank you.

