<?php

namespace Askedio\Laravel5ApiController\Exceptions;

use Exception;

class DefaultException extends Exception
{

    /**
     * @return void
     */
    public function __construct()
    {

        parent::__construct();
    }
}
