<?php

namespace Askedio\Laravel5ApiController\Exceptions;

class InternalServerErrorException extends JsonException
{
    /**
     * @var string
     */
    protected $status = 500;

    /**
     * @return void
     */
    public function __construct()
    {
        $message = $this->build(func_get_args());

        parent::__construct($message);
    }
}
