<?php

namespace Askedio\Laravel5ApiController\Traits;

use Askedio\Laravel5ApiController\Exceptions\NotAcceptableException;
use Askedio\Laravel5ApiController\Exceptions\InvalidAttributeException;
use Askedio\Laravel5ApiController\Helpers\Api;
use Askedio\Laravel5ApiController\Helpers\ApiController;
use Askedio\Laravel5ApiController\Helpers\ApiException;
use Askedio\Laravel5ApiController\Helpers\JsonResponse;
use Askedio\Laravel5ApiController\Transformers\Transformer;

trait ControllerTrait
{
    /** @var $results */
    private $results;

    public function __construct()
    {
        if (isset($this->version) && Api::getVersion() != $this->version) {
            ApiException::setDetails('/application/vnd.api.'.$this->version.'+json');
            throw new NotAcceptableException('not-acceptable');
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

    public function show($id)
    {
        return $this->render([
          'success' => 200,
          'error'   => 404,
          'results' => $this->results->show($id),
        ]);
    }

    public function update($id)
    {
        return $this->render([
          'success' => 200,
          'error'   => 500,
          'results' => $this->results->update($id),
        ]);
    }

    public function destroy($id)
    {
        return $this->render([
          'success' => 200,
          'error'   => 404,
          'data'    => $this->results->show($id),
          'results' => $this->results->destroy($id),
        ]);
    }

    private function render($data)
    {
        if (!isset($data['results']['errors'])) {
            if($data['results']){
              $_results = isset($data['data']) ? $data['data'] : $data['results'];
              return JsonResponse::render($data['success'], Transformer::render($_results));
            } else {
              ApiException::setDetails(['errors' => $data['error']]);
              throw new InvalidAttributeException('invalid_attribute', $data['error']);
            }
        } else {
            ApiException::setDetails(['errors' => $data['results']['errors']]);
            throw new InvalidAttributeException('invalid_attribute', 403);
        }
    }
}
