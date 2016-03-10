<?php

namespace Askedio\Laravel5ApiController\Exceptions;

class ForbiddenException extends JsonException
{
    /**
     * @var string
     */
    protected $status = '403';

    /**
     * @return void
     */
    public function __construct()
    {
        $message = $this->build(func_get_args());

        parent::__construct($message);
    }
}
