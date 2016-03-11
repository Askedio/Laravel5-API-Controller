<?php

namespace Askedio\Laravel5ApiController\Helpers;

use Askedio\Laravel5ApiController\Exceptions\InternalServerErrorException;
use Askedio\Laravel5ApiController\Exceptions\InvalidAttributeException;
use Askedio\Laravel5ApiController\Exceptions\NotFoundException;

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
     * Render error codes.
     *
     * @param int   $code
     * @param mixed $errors
     *
     * @return void
     */
    public static function render($code, $errors = false)
    {
        switch ($code) {
        case 404:
          throw new NotFoundException('not_found');
        break;
        case 500:
          throw new InternalServerErrorException('internal_server_error');
        break;
        case 403:
          self::setDetails(['errors' => $errors]);
          throw new InvalidAttributeException('invalid_attribute', $code);
        break;
      }
    }

    /**
     * Build a JsonResponse of errors.
     *
     * @param array $settings
     *
     * @return Askedio\Laravel5ApiController\Helpers\JsonResponse
     */
    public static function build($settings)
    {
        return JsonResponse::render(['errors' => self::details($settings)]);
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
