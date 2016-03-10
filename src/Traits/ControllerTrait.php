<?php

namespace Askedio\Laravel5ApiController\Traits;

use Askedio\Laravel5ApiController\Exceptions\NotAcceptableException;
use Askedio\Laravel5ApiController\Helpers\ApiHelper;
use Askedio\Laravel5ApiController\Helpers\ControllerHelper;

trait ControllerTrait
{
    private $results;

    public function __construct()
    {
        if (isset($this->version) && ApiHelper::getVersion() != $this->version) {
            throw new NotAcceptableException('not-acceptable', '/application/vnd.api.'.$this->version.'+json');
        }

        $this->results = new ControllerHelper($this->modal);
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
          'data'    => $this->render->show($id),
          'results' => $this->results->destroy($id),
        ]);
    }

    private function render($data)
    {
        if (!isset($data['results']['errors'])) {
            return $data['results']
              ? ApiHelper::success($data['success'], isset($data['data']) ? $data['data'] : $data['results'])
              : ApiHelper::error($data['error']);
        } else {
            return ApiHelper::error(403, $data['results']['errors']);
        }
    }
}
