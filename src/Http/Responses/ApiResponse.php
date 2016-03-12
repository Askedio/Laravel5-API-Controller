<?php

namespace Askedio\Laravel5ApiController\Http\Responses;

use Askedio\Laravel5ApiController\Helpers\Api;
use Response;

class ApiResponse extends Response
{
    /**
     * Change Content-Type for jsonapi response macro.
     *
     * @param int    $code
     * @param string $value
     *
     * @return Illuminate\Http\Response
     */
    public static function macro($code, $value)
    {
        return self::json($value, $code, [
          'Content-Type' => config('jsonapi.content_type'),
        ], true);
    }

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
