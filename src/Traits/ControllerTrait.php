<?php

namespace Askedio\Laravel5ApiController\Traits;

use Askedio\Laravel5ApiController\Exceptions\ApiException;
use Askedio\Laravel5ApiController\Exceptions\InvalidAttributeException;
use Askedio\Laravel5ApiController\Exceptions\NotAcceptableException;
use Askedio\Laravel5ApiController\Helpers\Api;
use Askedio\Laravel5ApiController\Helpers\ApiController;
use Askedio\Laravel5ApiController\Http\Responses\ApiResponse;
use Askedio\Laravel5ApiController\Transformers\Transformer;

trait ControllerTrait
{
    /** @var $results */
    private $results;

    public function __construct()
    {
        if (isset($this->version) && Api::getVersion() != $this->version) {
            $exception = new NotAcceptableException('not-acceptable');
            throw $exception->withDetails('/application/vnd.api.'.$this->version.'+json');
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

    public function show($_id)
    {
        return $this->render([
          'success' => 200,
          'error'   => 404,
          'results' => $this->results->show($_id),
        ]);
    }

    public function update($_id)
    {
        return $this->render([
          'success' => 200,
          'error'   => 500,
          'results' => $this->results->update($_id),
        ]);
    }

    public function destroy($_id)
    {
        return $this->render([
          'success' => 200,
          'error'   => 404,
          'data'    => $this->results->show($_id),
          'results' => $this->results->destroy($_id),
        ]);
    }

    private function render($data)
    {
        if (!isset($data['results']['errors'])) {
            if ($data['results']) {
                $_results = isset($data['data']) ? $data['data'] : $data['results'];

                return ApiResponse::render($data['success'], Transformer::render($_results));
            }

            $exception = new InvalidAttributeException('invalid_attribute', $data['error']);
            throw $exception->withDetails(['errors' => $data['error']]);
        }

        $exception = new InvalidAttributeException('invalid_attribute', 403);
        throw $exception->withDetails(['errors' => $data['results']['errors']]);
    }
}
