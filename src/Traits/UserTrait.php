<?php

namespace Askedio\Laravel5ApiController\Traits;

use Illuminate\Foundation\Auth\User as Authenticatable;

trait  UserTrait 
{

    public function getRule($rule)
    {
        return $this->rules[$rule];   
    }
}
