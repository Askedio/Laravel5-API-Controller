<?php

namespace Askedio\Laravel5ApiController\Exceptions;

class NotAcceptableException extends JsonException
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
        $message = $this->build(func_get_args());

        parent::__construct($message);
    }
}