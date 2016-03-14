<?php

namespace Askedio\Tests\App\Http\Controllers;

use Askedio\Laravel5ApiController\Traits\ControllerTrait;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
  use ControllerTrait;
    public $model = \Askedio\Tests\App\User::class;

    public $version = 'v1';
}
