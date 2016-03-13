<?php

namespace Askedio\Laravel5ApiController\Exceptions;

use Exception;

abstract class ApiException extends Exception
{
    /** @var array */
    private $exceptionDetails;

    /**
     * @var string
     */
    protected $error;

    /**
     * @var int
     */
    protected $status;

    /**
     * Get the error.
     *
     * @return mixed
     */
    public function getError()
    {
        return $this->getDetails($this->error);
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
     * Store exception details.
     *
     * @param mixed $details
     */
    public function withDetails($details)
    {
        $this->exceptionDetails = $details;

        return $this;
    }

    /**
     * Build the error results.
     *
     * @return array
     */
    public function getDetails($_template)
    {
        $_results = [];

        $_details = $this->exceptionDetails;

      /* Pre-rendered errors */
      if (isset($_details['errors']) && is_array($_details['errors'])) {
          foreach ($_details['errors'] as $detail) {
              $_results[] = $detail;
          }

          return $_results;
      }

      /* Not pre-rendered errors, build from template */
      if (!is_array($_details)) {
          $_details = [$_details];
      }

        if (!empty($_details)) {
            foreach ($_details as $detail) {
                $_results[] = $this->item($_template, $detail);
            }
        }

        return $_results;
    }

    /**
     * Render the item.
     *
     * @return array
     */
    private function item($_template, $detail)
    {


        $_insert = $_template;
        $_replace = $_template['detail'];

        $_insert['detail'] = vsprintf($_replace, $detail);
        if (isset($_template['source'])) {
            $_insert['source'] = [];
            $_insert['source'][$_template['source']['type']] = vsprintf($_template['source']['value'], $detail);
        }

        return $_insert;
    }

    /**
     * Build the Exception details from the custom exception class.
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
     * Generate settings array from errors config.
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
