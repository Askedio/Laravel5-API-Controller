<?php

namespace Askedio\Tests\App\Http\Controllers;

use Askedio\Laravel5ApiController\Http\Controllers\BaseController;

class ProfileController extends BaseController
{
    public $model = \Askedio\Tests\App\Profiles::class;

    public $version = 'v1';
}
