<?php

namespace Askedio\Laravel5ApiController\Exceptions;

class NotAcceptableException extends ApiException
{
    /**
     * @var string
     */
    protected $status = 406;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->build(func_get_args());

        parent::__construct();
    }
}
