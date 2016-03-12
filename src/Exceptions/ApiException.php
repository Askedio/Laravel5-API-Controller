<?php

namespace Askedio\Laravel5ApiController\Exceptions;

class ApiException extends JsonException
{
    /** @var array */
    private $exceptionDetails;

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
        if (empty($detail)) {
            return $_template;
        }

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
