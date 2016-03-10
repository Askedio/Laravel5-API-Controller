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
Add the use statements to your Model to enable the Api and Search features.
~~~
class User extends Authenticatable
{
   
    use \Askedio\Laravel5ApiController\Traits\ApiTrait;
    use \Askedio\Laravel5ApiController\Traits\SearchableTrait;
    ...
~~~
You can set more details, like searching, includes, rules, primarykey and transform in the Modal Options.
[All Modal Options](https://github.com/Askedio/Laravel5-API-Controller/wiki/Modals)


## Routes, ie: app/Http/routes.php
Enable the jsonapi middleware on your route. 
~~~
Route::group(['prefix' => 'api', 'middleware' => ['api', 'jsonapi']], function()
{
  Route::resource('admin/user', 'Api\UserController');
});
~~~
You can also do [Version Control](https://github.com/Askedio/Laravel5-API-Controller/wiki/Version-Control)


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
My goal is a plug-n-play json api for Laravel. You shouldn't need to configure much of anything to enable the api on your models but if you still want advanced features like relations, searching, etc, you get that too.

I am still working to include all of the json api spec features into this api.

If you have any comments, opinions or can code review please reach me here or on twitter, @askedio.

Thank you.

