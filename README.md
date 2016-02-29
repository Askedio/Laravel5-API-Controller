# Laravel 5.2 API Controller
A simple controller to help provide quick access to Modal functions and validation, mostly for a CRUD API.

# Installation

### Install Package
~~~
composer require askedio/laravel5-api-controller:dev-mster
~~~

### Modify your Modal, ie: app\User.php
~~~
class User extends Authenticatable
{
   
     use \Askedio\LaravelVendorPackage\Traits\UserTrait;
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
