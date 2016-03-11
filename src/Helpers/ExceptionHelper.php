<?php

namespace Askedio\Laravel5ApiController\Helpers;

use Askedio\Laravel5ApiController\Exceptions\InternalServerErrorException;
use Askedio\Laravel5ApiController\Exceptions\InvalidAttributeException;
use Askedio\Laravel5ApiController\Exceptions\NotFoundException;


class ExceptionHelper
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
     * Get Exception details.
     *
     * @return mixed
     */
    public static function getDetails()
    {
        return self::$exceptionDetails;
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

}
