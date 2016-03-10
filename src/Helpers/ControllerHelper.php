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
        $results = $this->_modal->setSort($this->request->input('sort'));

        if ($this->request->input('search') && $this->_modal->isSearchable()) {
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

/*
TO-DO:
needs results like

    {
      "code":   "225",
      "source": { "pointer": "/data/attributes/password" },
      "title": "Passwords must contain a letter, number, and punctuation character.",
      "detail": "The password provided is missing a punctuation character."
    },



*/
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
