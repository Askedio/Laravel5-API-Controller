<?php

namespace Askedio\Laravel5ApiController\Helpers;

use Askedio\Laravel5ApiController\Exceptions\InternalServerErrorException;
use Askedio\Laravel5ApiController\Exceptions\InvalidAttributeException;
use Askedio\Laravel5ApiController\Exceptions\NotFoundException;
use Askedio\Laravel5ApiController\Transformers\Transformer;
use Request;

class ApiHelper
{
    private static $version;

    private static $exceptionDetails;

    public static function setExceptionDetails($details)
    {
        self::$exceptionDetails = $details;
    }

    public static function getExceptionDetails()
    {
        return self::$exceptionDetails;
    }

    public static function getVersion()
    {
        return self::$version ?: config('jsonapi.version');
    }

    public static function setVersion($version)
    {
        self::$version = $version;
    }

    public static function error($code, $errors = false)
    {
        switch ($code) {
        case 404:
          throw new NotFoundException('not_found');
        break;
        case 500:
          throw new InternalServerErrorException('internal_server_error');
        break;
        case 403:
          self::setExceptionDetails(['errors' => $errors]);
          throw new InvalidAttributeException('invalid_attribute', $code);
        break;
      }
    }

    public static function success($code, $results)
    {
        return response()->jsonapi($code, Transformer::render($results));
    }

    public static function includes()
    {
        return Request::input('include') ? explode(',', Request::input('include')) : [];
    }

    public static function fields()
    {
        $_results = [];
        foreach (array_filter(Request::input('fields', [])) as $type => &$members) {
            foreach (explode(',', $members) as $member) {
                $_results[$type][] = $member;
            }
        }

        return $_results;
    }

    /**
     * @return array
     */
    public static function renderJsonSpi($data = [])
    {
        return array_merge($data, [
          'jsonapi' => [
            'version'   => config('jsonapi.json_version', '1.0'),
            'self'      => self::getVersion(),
          ],
        ]);
    }
}
