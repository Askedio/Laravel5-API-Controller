<?php

namespace Askedio\Laravel5ApiController\Http\Responses;

use Askedio\Laravel5ApiController\Helpers\Api;
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
    public function jsonapi($code, $results)
    {
        return response()->json($this->jsonapiData($results), $code, [
            'Content-Type' => config('jsonapi.content_type'),
        ], true);
    }

    /**
     * Render the output for the json api.
     *
     * @return array
     */
    public function jsonapiData($data = [])
    {
        return array_merge($data, [
            'jsonapi' => [
                'version' => config('jsonapi.json_version', '1.0'),
                'self'    => app('api')->getVersion(),
            ],
        ]);
    }
}
