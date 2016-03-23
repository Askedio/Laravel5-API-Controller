<?php

namespace Askedio\Tests\App\Http\Controllers;

use Askedio\Laravel5ApiController\Traits\ControllerTrait;
use Illuminate\Routing\Controller;

class ProfileController extends Controller
{
    use ControllerTrait;
    
    public $model = \Askedio\Tests\App\Profiles::class;

    public $version = 'v1';

    public $auth = true;
}
