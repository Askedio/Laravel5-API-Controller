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
        return response()->jsonapi(200, Transformer::convert($results));
    }

    public static function throwException($code, $message = false)
    {
        return response()->jsonapi($code, ['errors' => [
            'status' => $code,
            'detail' => $message,
          ],
       ]);
    }
}
