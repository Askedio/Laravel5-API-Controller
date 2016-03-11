<?php

namespace Askedio\Laravel5ApiController\Helpers;

use Askedio\Laravel5ApiController\Exceptions\InternalServerErrorException;
use Askedio\Laravel5ApiController\Exceptions\InvalidAttributeException;
use Askedio\Laravel5ApiController\Exceptions\NotFoundException;
use Askedio\Laravel5ApiController\Transformers\Transformer;
use Request;

class ApiHelper
{
    /** @var string */
    private static $version;

    /** @var array */
    private static $exceptionDetails;

    /**
     * Store exception details.
     *
     * @param mixed $details
     */
    public static function setExceptionDetails($details)
    {
        self::$exceptionDetails = $details;
    }

    /**
     * Get Exception details.
     *
     * @return mixed
     */
    public static function getExceptionDetails()
    {
        return self::$exceptionDetails;
    }

    /**
     * Get API version.
     *
     * @return string
     */
    public static function getVersion()
    {
        return self::$version ?: config('jsonapi.version');
    }

    /**
     * Set version.
     *
     * @param string $version
     *
     * @return void
     */
    public static function setVersion($version)
    {
        self::$version = $version;
    }

    /**
     * Render error codes.
     *
     * @param int   $code
     * @param mixed $errors
     *
     * @return void
     */
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

    /**
     * Successfull Event.
     *
     * @param int   $code
     * @param mixed $results
     *
     * @return Illuminate\Http\Response
     */
    public static function success($code, $results)
    {
        return response()->jsonapi($code, Transformer::render($results));
    }

    /**
     * List of included options from input.
     *
     * @return Illuminate\Http\Request
     */
    public static function includes()
    {
        return Request::input('include') ? explode(',', Request::input('include')) : [];
    }

    /**
     * List of fields from input.
     *
     * @return array
     */
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
     * Render the output for the json api.
     *
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
