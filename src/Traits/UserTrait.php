<?php

namespace Askedio\Laravel5ApiController\Traits;

trait UserTrait
{
    public function getRule($rule)
    {
        return $this->rules[$rule];
    }
}
