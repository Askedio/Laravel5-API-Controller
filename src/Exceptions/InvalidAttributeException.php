<?php

namespace Askedio\Laravel5ApiController\Exceptions;

class InvalidAttributeException extends ApiException
{
    /**
     * @var string
     */
    protected $status = 403;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->build(func_get_args());

        parent::__construct();
    }
}
