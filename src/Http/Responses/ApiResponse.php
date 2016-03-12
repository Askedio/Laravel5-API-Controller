<?php

namespace Askedio\Laravel5ApiController\Http\Responses;

use Api;
use Response;

class ApiResponse extends Response
{
    /**
     * Render json api output.
     *
     * @param int   $code
     * @param array $results
     *
     * @return Illuminate\Http\Response
     */
    public static function render($code, $results)
    {
        return self::jsonapi($code, self::build($results));
    }

    /**
     * Render the output for the json api.
     *
     * @return array
     */
    public static function build($data = [])
    {
        return array_merge($data, [
          'jsonapi' => [
            'version'   => config('jsonapi.json_version', '1.0'),
            'self'      => Api::getVersion(),
          ],
        ]);
    }
}
