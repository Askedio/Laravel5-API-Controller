<?php

namespace Askedio\Laravel5ApiController\Helpers;

use Askedio\Laravel5ApiController\Transformers\Transformer;


class ApiHelper
{
    private static $modal;

    public static function setModal($modal)
    {
      self::$modal = $modal;
    }

    public static function error($errors)
    {
        return response()->jsonapi(200, ['errors' => $errors]);
    }

    public static function success($results)
    {
       // no alias/facade
       $transformer = new Transformer();

       return response()->jsonapi(200, $transformer->modal(self::$modal, $results));
    }

    public static function throwException($code, $message = false)
    {
       return response()->jsonapi($code, ['errors' => $message]);
    }
}
