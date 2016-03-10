<?php

namespace Askedio\Laravel5ApiController\Exceptions;

class BadRequestException extends JsonException
{
    /**
     * @var string
     */
    protected $status = 400;

    /**
     * @return void
     */
    public function __construct()
    {
        $message = $this->build(func_get_args());

        parent::__construct($message);
    }
}
