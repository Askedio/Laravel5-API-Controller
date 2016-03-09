<?php

namespace Askedio\Laravel5ApiController\Traits;

trait ApiTrait
{
    public function getRule($rule)
    {
        return $this->rules[$rule];
    }

    public function getId()
    {
        return $this->id ? : 'id';
    }

}
