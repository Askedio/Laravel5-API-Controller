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
    public function getDetails($template)
    {
        $details = $this->exceptionDetails;

      /* Pre-rendered errors */
      if (isset($details['errors']) && is_array($details['errors'])) {
          return $details['errors'];
      }

      /* Not pre-rendered errors, build from template */
      if (!is_array($details)) {
          $details = [$details];
      }

        return array_map(function ($detail) use ($template) {
        return $this->item($template, $detail);
      }, $details);
    }

    /**
     * Render the item.
     *
     * @return array
     */
    private function item($template, $detail)
    {
        $insert = $template;
        $_replace = $template['detail'];

        $insert['detail'] = vsprintf($_replace, $detail);
        if (isset($template['source'])) {
            $insert['source'] = [];
            $insert['source'][$template['source']['type']] = vsprintf($template['source']['value'], $detail);
        }

        return $insert;
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

        $settings = $this->settings($args);
        $this->error = $settings;
        $this->status = $settings['code'];
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
