<?php

namespace Askedio\Laravel5ApiController\Exceptions;

class ApiException
{
    /** @var array */
    private static $exceptionDetails;

    /**
     * Store exception details.
     *
     * @param mixed $details
     */
    public static function setDetails($details)
    {
        self::$exceptionDetails = $details;
    }

    /**
     * A bit pointless now..
     *
     * @param array $settings
     *
     * @return array
     */
    public static function build($settings)
    {
        return self::details($settings);
    }

    /**
     * Build the error results.
     *
     * @return array
     */
    public static function details($_template)
    {
        $_results = [];

        $_details = self::$exceptionDetails;

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
                  $_results[] = self::item($_template, $detail);
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
    private static function item($_template, $detail)
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
