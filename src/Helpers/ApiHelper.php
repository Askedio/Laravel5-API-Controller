<?php

namespace Askedio\Laravel5ApiController\Helpers;

class ApiHelper
{
    public static function error($errors)
    {
        return ['success' => false, 'errors' => $errors];
    }

    public static function success($results)
    {
        return ['success' => true, 'results' => $results];
    }

    public static function throwException($type, $message = false)
    {
        abort($type, $message);
    }
}

