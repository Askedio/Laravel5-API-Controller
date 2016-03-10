<?php

namespace Askedio\Laravel5ApiController\Helpers;

use Askedio\Laravel5ApiController\Transformers\Transformer;
use Request;

class ApiHelper
{
    public static function error($code, $errors)
    {
        return response()->jsonapi($code,
          ['errors' => [
            'status' => $code,
            'detail' => $errors,
          ],
        ]);
    }

    public static function success($code, $results)
    {
        return response()->jsonapi($code, Transformer::convert($results));
    }

    public static function includes()
    {
        $include = Request::input('include');
        if (!is_string($include)) {
            return false;
        } else {
            return explode(',', $include);
        }
    }

    public static function fields()
    {
        $_results = [];
        $_fields = Request::input('fields');
        if (is_array($_fields)) {
            $_fields = array_filter($_fields);
            foreach ($_fields as $type => &$members) {
                $members = array_map('trim', explode(',', $members));
                foreach ($members as $member) {
                    $_results[$type][] = $member;
                }
            }
        }

        return $_results;
    }
}
