<?php

namespace Askedio\Laravel5ApiController\Traits;

use Askedio\Laravel5ApiController\Helpers\ApiHelper;
use Askedio\Laravel5ApiController\Helpers\ControllerHelper;
use Askedio\Laravel5ApiController\Exceptions\BadRequestException;
use Illuminate\Http\Request;

trait ControllerTrait
{
    private $_modal;
    private $request;
    private $helper;
    private $validation_error = 403;

    public function __construct(Request $request)
    {
        $this->_modal = new $this->modal();
        $this->request = $request;
        $this->render = new ControllerHelper($request, $this->_modal);
       
        $this->validateIncludes();
    }

    private function validateIncludes()
    {
        $allowed = $this->_modal->getIncludes();
        foreach(ApiHelper::includes() as $include){
          if(!in_array($include, $allowed)) throw new BadRequestException('bad_request');
        }
    }

    public function index(Request $request)
    {
        return $this->results([
          'success' => 200,
          'error'   => 500,
          'results' => $this->render->index(),
        ]);
    }

    public function store()
    {
        return $this->results([
          'success' => 200,
          'error'   => 500,
          'results' => $this->render->store(),
        ]);
    }

    public function show($id)
    {
        return $this->results([
          'success' => 200,
          'error'   => 404,
          'results' => $this->render->show($id),
        ]);
    }

    public function update($id)
    {
        return $this->results([
          'success' => 200,
          'error'   => 500,
          'results' => $this->render->update($id),
        ]);
    }

    public function destroy($id)
    {
        return $this->results([
          'success' => 200,
          'error'   => 404,
          'data'    => $this->render->show($id),
          'results' => $this->render->destroy($id),
        ]);
    }

    private function results($data)
    {
        if (!isset($data['results']['errors'])) {
            return $data['results']
              ? ApiHelper::success($data['success'], isset($data['data']) ? $data['data'] : $data['results'])
              : ApiHelper::error($data['error'], isset($data['errors']) ? $data['errors'] : '');
        } else {
            return ApiHelper::error($this->validation_error, $data['results']['errors']);
        }
    }
}
