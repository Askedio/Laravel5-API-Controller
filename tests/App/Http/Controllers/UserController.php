<?php

namespace Askedio\Tests\App\Http\Controllers;

use Askedio\Laravel5ApiController\Http\Controllers\BaseController;

class UserController extends BaseController
{
    public $model = \Askedio\Tests\App\User::class;

    public $version = 'v1';
}
