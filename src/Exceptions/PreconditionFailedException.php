<?php namespace Askedio\Laravel5ApiController\Exceptions;

class PreconditionFailedException extends JsonException
{
    /**
     * @var string
     */
    protected $status = '412';

    /**
     * @return void
     */
    public function __construct()
    {
        $message = $this->build(func_get_args());
        parent::__construct($message);
    }
}
