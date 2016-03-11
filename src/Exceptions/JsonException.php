<?php

namespace Askedio\Laravel5ApiController\Exceptions;

use Askedio\Laravel5ApiController\Helpers\ApiException;
use Askedio\Laravel5ApiController\Helpers\JsonResponse;
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

      // nothing to build if no type
      if (!isset($args[0])) {
          return false;
      }

        $settings = $this->settings($args);
        $_results = $this->details($settings);

        $this->error = JsonResponse::render(['errors' => $_results]);

        $this->status = $settings['code'];
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

    /**
     * Build the error results.
     *
     * @return array
     */
    private function details($_template)
    {
        $_results = [];

        $_details = ApiException::getDetails();

      /* Pre-rendered errors */
      if (isset($_details['errors']) && is_array($_details['errors'])) {
          foreach ($_details['errors'] as $detail) {
              $_results[] = $detail;
          }
      /* Not pre-rendered errors, build from template */
      } else {
          if (!is_array($_details)) {
              $_details = [$_details];
          }
          if (!empty($_details)) {
              foreach ($_details as $detail) {
                  $_results[] = $this->item($_template, $detail);
              }
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
}
