<?php

namespace Askedio\Laravel5ApiController\Helpers;

use Askedio\Laravel5ApiController\Transformers\Transformer;

/* TO-DO: probably better off extending the JsonResponse class to add our wrappers? or do something else cause wtf is success vs error, transformer vs no - so same fkn thing... */
class JsonResponse
{
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

    public static function error($code, $results)
    {
        return response()->jsonapi($code, $results);
    }

    /**
     * Render the output for the json api.
     *
     * @return array
     */
    public static function render($data = [])
    {
        return array_merge($data, [
          'jsonapi' => [
            'version'   => config('jsonapi.json_version', '1.0'),
            'self'      => Api::getVersion(),
          ],
        ]);
    }
}
