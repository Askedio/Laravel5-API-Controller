<?php

namespace Askedio\Laravel5ApiController\Helpers;

use Askedio\Laravel5ApiController\Transformers\Transformer;

class ApiHelper
{
    public static function error($code, $errors)
    {
        return response()->jsonapi($code, ['errors' => [
            'status' => $code, 
            'detail' => $errors
          ]
        ]);
    }

    public static function success($code, $results)
    {
        return response()->jsonapi($code, Transformer::convert($results));
    }


}
