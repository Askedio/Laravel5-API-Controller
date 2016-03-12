<?php

namespace Askedio\Laravel5ApiController\Exceptions;

use Exception;

abstract class JsonException extends Exception
{
    /**
     * @var string
     */
    protected $error;

    /**
     * @var int
     */
    protected $status;

    /**
     * @param @string $message
     *
     * @return void
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }

    /**
     * Get the error.
     *
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Get the status.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return (int) $this->status;
    }

    /**
     * Build the Exception.
     *
     * @return void
     */
    protected function build(array $args)
    {

      /* Nothing to build if no type. */
      if (!isset($args[0])) {
          return false;
      }

        $_settings = $this->settings($args);
        $this->error = $_settings;
        $this->status = $_settings['code'];
    }

    /**
     * Generate settings array.
     *
     * @return array
     */
    private function settings($args)
    {
        $_base = [
        'title'  => '',
        'detail' => '',
        'code'   => isset($args[1]) ? $args[1] : $this->status,
      ];

        return array_merge($_base, config(sprintf('errors.%s', $args[0]), []));
    }
}
