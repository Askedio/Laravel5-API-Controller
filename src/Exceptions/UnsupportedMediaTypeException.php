<?php

namespace Askedio\Laravel5ApiController\Exceptions;

class UnsupportedMediaTypeException extends ApiException
{
    /**
     * @var string
     */
    protected $status = 415;

    /**
     * @return void
     */
    public function __construct()
    {
        $message = $this->build(func_get_args());

        parent::__construct($message);
    }
}
