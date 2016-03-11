<?php

namespace Askedio\Laravel5ApiController\Traits;

use Askedio\Laravel5ApiController\Exceptions\NotAcceptableException;
use Askedio\Laravel5ApiController\Helpers\Api;
use Askedio\Laravel5ApiController\Helpers\ExceptionHelper;
use Askedio\Laravel5ApiController\Helpers\ControllerHelper;
use Askedio\Laravel5ApiController\Helpers\JsonHelper;

trait ControllerTrait
{
    /** @var $results */
    private $results;

    public function __construct()
    {
        if (isset($this->version) && Api::getVersion() != $this->version) {
            ExceptionHelper::setDetails('/application/vnd.api.'.$this->version.'+json');
            throw new NotAcceptableException('not-acceptable');
        }

        $this->results = new ControllerHelper($this->model);
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
            return $data['results']
              ? JsonHelper::success($data['success'], isset($data['data']) ? $data['data'] : $data['results'])
              : ExceptionHelper::render($data['error']);
        } else {
            return ExceptionHelper::render(403, $data['results']['errors']);
        }
    }
}
