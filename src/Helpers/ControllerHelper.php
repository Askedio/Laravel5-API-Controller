<?php

namespace Askedio\Laravel5ApiController\Helpers;

use Validator;

class ControllerHelper
{
    private $_modal;
    private $request;

    public function __construct($request, $modal)
    {
        $this->_modal = new $modal();
        $this->request = $request;
    }

    public function index()
    {
        $results = $this->_modal
          ->setSort($this->request->input('sort'))
          ->setFields($this->request->input('fields'));

        if ($this->request->input('search')) {
            $results->search($this->request->input('search'));
        }

        return $results->paginate(($this->request->input('limit') ?: '10'));
    }

    public function store()
    {
        if ($errors = $this->validate('create')) {
            return ['errors' => $errors];
        } else {
            return $this->_modal->create($this->cleanRequest());
        }
    }

    public function show($id)
    {
        return $this->_modal->find($id);
    }

    public function update($id)
    {
        if ($errors = $this->validate('update')) {
            return ['errors' => $errors];
        } else {
            $_modal = $this->_modal->find($id);

            return $_modal
              ? (
                  $_modal->update($this->cleanRequest())
                  ? $_modal
                  : false
                )
              : false;
        }
    }

    public function destroy($id)
    {
        $_modal = $this->_modal->find($id);

        return $_modal ? $_modal->delete() : false;
    }

    private function cleanRequest()
    {
        $_allowed = $this->_modal->getFillable();
        $request = $this->request->all();

        foreach ($request as $var => $val) {
            if (!in_array($var, $_allowed)) {
                unset($request[$var]);
            }
        }

        return $request;
    }

    private function validate($action)
    {
        $validator = Validator::make($this->request->all(), $this->_modal->getRule($action));
        $_errors = [];

        /* Clean up valiation, had issues with parsing in jquery */
        $e = $validator->errors()->toArray();
        foreach ($validator->errors()->toArray() as $_field => $_err) {
            $_errors[] = [
            'field' => $_field,
            'error' => implode(' ', $_err),
          ];
        }

        return $validator->fails() ? $_errors : false;
    }
}
