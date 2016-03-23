<?php

namespace Askedio\Laravel5ApiController\Exceptions;

class UnauthorizedHttpException extends ApiException
{
    /**
     * @var string
     */
    protected $status = 401;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->build(func_get_args());

        parent::__construct();
    }
}
