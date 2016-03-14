<?php

namespace Askedio\Laravel5ApiController\Traits;

use Askedio\Laravel5ApiController\Exceptions\InvalidAttributeException;
use Askedio\Laravel5ApiController\Exceptions\NotAcceptableException;
use Askedio\Laravel5ApiController\Helpers\Api;
use Askedio\Laravel5ApiController\Helpers\ApiController;
use Askedio\Laravel5ApiController\Transformers\Transformer;

trait ControllerTrait
{
    /** @var $results */
    private $results;

    public function __construct()
    {
        if (isset($this->version) && app('api')->getVersion() != $this->version) {
            throw (new NotAcceptableException('not-acceptable'))->withDetails('/application/vnd.api.'.$this->version.'+json');
        }

        $this->results = new ApiController($this->model);
    }

    public function index()
    {
        return $this->render([
          'success' => 200,
          'error'   => 500,
          'results' => $this->results->index(),
        ]);
    }

    public function store()
    {
        return $this->render([
          'success' => 200,
          'error'   => 500,
          'results' => $this->results->store(),
        ]);
    }

    public function show($idd)
    {
        return $this->render([
          'success' => 200,
          'error'   => 404,
          'results' => $this->results->show($idd),
        ]);
    }

    public function update($idd)
    {
        return $this->render([
          'success' => 200,
          'error'   => 500,
          'results' => $this->results->update($idd),
        ]);
    }

    public function destroy($idd)
    {
        return $this->render([
          'success' => 200,
          'error'   => 404,
          'data'    => $this->results->show($idd),
          'results' => $this->results->destroy($idd),
        ]);
    }

    private function render($data)
    {
        if (!isset($data['results']['errors'])) {
            if ($data['results']) {
                $results = isset($data['data']) ? $data['data'] : $data['results'];

                $transformer = new Transformer();

                return response()->jsonapi($data['success'], $transformer->render($results));
            }

            throw (new InvalidAttributeException('invalid_attribute', $data['error']))->withDetails(['errors' => $data['error']]);
        }

        throw (new InvalidAttributeException('invalid_attribute', 403))->withDetails(['errors' => $data['results']['errors']]);
    }
}
