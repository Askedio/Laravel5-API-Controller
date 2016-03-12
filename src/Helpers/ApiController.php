<?php

namespace Askedio\Laravel5ApiController\Helpers;

use Request;
use Validator;

class ApiController
{
    /** @var object */
    private $model;

    /**
     * @param modelclass.. $model
     */
    public function __construct($model)
    {
        $this->model = new $model();
        $this->model->checkIncludes();
    }

    /**
     * index.
     *
     * @return pagination class..
     */
    public function index()
    {
        $results = $this->model->setSort(Request::input('sort'));

        if (Request::input('search') && $this->model->isSearchable()) {
            $results->search(Request::input('search'));
        }

        return $results->paginate((Request::input('limit') ?: '10'));
    }

    /**
     * Store.
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function store()
    {
        if ($errors = $this->validate('create')) {
            return ['errors' => $errors];
        } else {
            return $this->model->create($this->cleanRequest());
        }
    }

    /**
     * Show.
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function show($id)
    {
        return $this->model->find($id);
    }

    /**
     * Update.
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function update($id)
    {
        if ($errors = $this->validate('update')) {
            return ['errors' => $errors];
        } else {
            $_model = $this->model->find($id);

            return $_model
              ? (
                  $_model->update($this->cleanRequest())
                  ? $_model
                  : false
                )
              : false;
        }
    }

    /**
     * Destroy.
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function destroy($id)
    {
        $_model = $this->model->find($id);

        return $_model ? $_model->delete() : false;
    }

    /**
     * Clean Request Fields.
     *
     * @return array
     */
    private function cleanRequest()
    {
        $_allowed = $this->model->getFillable();
        $request = Request::json()->all();

        // TO-DO: laravel helper
        foreach ($request as $var => $val) {
            if (!in_array($var, $_allowed)) {
                unset($request[$var]);
            }
        }

        return $request;
    }

    /**
     * Validate Form.
     *
     * @param string $action
     *
     * @return array
     */
    private function validate($action)
    {
        $validator = Validator::make(Request::json()->all(), $this->model->getRule($action));
        $_errors = [];
        foreach ($validator->errors()->toArray() as $_field => $_err) {
            array_push($_errors, [
            // TO-DO: detect errors for a valid json api code
            //'code'   => 0,
            'source' => ['pointer' => $_field],
            'title'  => config('errors.invalid_attribute.title'),
            'detail' => implode(' ', $_err),
          ]);
        }

        return $validator->fails() ? $_errors : false;
    }
}
