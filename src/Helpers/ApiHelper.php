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
        $_results = ['success' => true];
        if(isset($results['data'])){
          $_results = array_merge($_results, $results);
        } else $_results['data'] = $results;
        return $_results;
    }

    public static function throwException($type, $message = false)
    {
        abort($type, $message);
    }
}
