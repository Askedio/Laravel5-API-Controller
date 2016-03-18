![laravel-cruddy](http://i.imgur.com/TmEh1m6.jpgg)
A really simple package that provides a CRUD JSON API for your Laravel 5 application.

[![Build Status](https://img.shields.io/travis/Askedio/Laravel5-API-Controller/master.svg?style=flat-square)](https://travis-ci.org/Askedio/Laravel5-API-Controller)
[![StyleCI](https://styleci.io/repos/52752552/shield)](https://styleci.io/repos/52752552)
[![Code Climate](https://codeclimate.com/github/Askedio/Laravel5-API-Controller/badges/gpa.svg)](https://codeclimate.com/github/Askedio/Laravel5-API-Controller)
[![Codacy Badge](https://api.codacy.com/project/badge/grade/c2f2291fe3af4ea3a511afa64ddc034b)](https://www.codacy.com/app/gcphost/Laravel5-API-Controller)
[![Codacy Badge](https://api.codacy.com/project/badge/coverage/c2f2291fe3af4ea3a511afa64ddc034b)](https://www.codacy.com/app/gcphost/Laravel5-API-Controller)

* [Live Demo](https://cruddy.io/app/)
* [Laravel 5.2 Example Package](https://github.com/Askedio/Laravel-5-CRUD-Example)
* Plays well with [jQuery CRUDdy](https://github.com/Askedio/jQuery-Cruddy)



# Installation
Read the [wiki](https://github.com/Askedio/Laravel5-API-Controller/wiki) for more details.

### Install Package
~~~
composer require askedio/laravel5-api-controller::0.0.6.x-dev
~~~




### Add to Providers: config/app.php
~~~
'providers' => [
    Askedio\Laravel5ApiController\Providers\GenericServiceProvider::class,
        ...
~~~




### Model: app/User.php
Add the use statements to your Model to enable the Api and Search features.
~~~
class User extends Authenticatable
{

    use \Askedio\Laravel5ApiController\Traits\ModelTrait;
    use \Askedio\Laravel5ApiController\Traits\SearchableTrait;
    ...
~~~
You can set more details, like searching, includes, rules, primarykey and transform in the [Model Options](https://github.com/Askedio/Laravel5-API-Controller/wiki/Models).

### Controller: app/Http/Controllers/Api/UserController.php
Create a custom controller for your API.
~~~
<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller as BaseController;

class UserController extends BaseController
{
    use \Askedio\Laravel5ApiController\Traits\ControllerTrait;
    public $modal = \App\User::class;

    /* Optional */
    public $version = 'v1';

}
~~~

### Routes: app/Http/routes.php
Create a prefixed group for your api and assign the api and jsonapi middlewares.
~~~
Route::group(['prefix' => 'api', 'middleware' => ['api', 'jsonapi']], function()
{
  Route::resource('admin/user', 'Api\UserController');
});
~~~
* Provides configurable strict mode to do Accept and Content-type matching.
* Provides [Version Control](https://github.com/Askedio/Laravel5-API-Controller/wiki/Version-Control)



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




### Allowed Query Paramaters
~~~
#Global
include: [comma delim list] ie: include=profiles,addresses
fields:  [array=comma delim list] ie: fields[profile]=id,name

# Lists
page:    [int]
limit:   [int]
sort:    [-]field
search:  [string]
~~~



# JSON API Spec
Supported:
* Content-type & Accept validation [ref](http://jsonapi.org/format/#content-negotiation)
* GET input variables validation
* Responses [ref](http://jsonapi.org/format/#document-resource-objects)
* Errors [ref](http://jsonapi.org/format/#errors)
* Includes [ref](http://jsonapi.org/format/#fetching-includes)
* Fieldsets [ref](http://jsonapi.org/format/#fetching-sparse-fieldsets)
* Pagination [ref](http://jsonapi.org/format/#fetching-pagination)
* Sorting [ref](http://jsonapi.org/format/#fetching-sorting)
* Form Validation [ref](http://jsonapi.org/examples/#error-objects-error-codes)
* Member Name Sanitization [ref](http://jsonapi.org/format/#document-member-names)

## Strict Mode
You can force Accept and Content-type matching by adding the following to your .env
~~~
JSONAPI_STRICT=true
~~~

Not Supported:
* PUT: I am using PATCH instead.
* PATCH/POST input variaibles: Currently accepting {var:val}




# Similar Packages
* https://github.com/nilportugues/laravel5-jsonapi
* https://github.com/dingo/api





# Comments
My goal is a plug-n-play json api for Laravel. You shouldn't need to configure much of anything to enable the api on your models but if you still want advanced features like relations, searching, etc, you get that too.

I am still working to include all of the json api spec features into this api.

If you have any comments, opinions or can code review please reach me here or on twitter, [@asked_io](https://twitter.com/asked_io). You can also follow me on my website, [asked.io](https://asked.io).


Thank you.
