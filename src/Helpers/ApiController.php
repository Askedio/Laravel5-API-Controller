<?php

namespace Askedio\Laravel5ApiController\Helpers;

use Askedio\Laravel5ApiController\Exceptions\InvalidAttributeException;

class ApiController
{
    /** @var object */
    private $model;

    /**
     * @param Controller $parent
     */
    public function __construct($parent)
    {
        $this->model = new $parent->model();

        new ApiValidation($this->model->getObjects());

        if($parent->getAuth()){
          $table = $this->model->getTable();
          $this->model = auth()->user()->$table();
        }
    }

    /**
     * index.
     *
     * @return pagination class..
     */
    public function index()
    {
        $results = $this->model->setSort(request()->input('sort'));

        if (request()->input('search') && $this->model->isSearchable()) {
            $results->search(request()->input('search'));
        }

        return $results->paginate(request()->input('page.size', 10), ['*'], 'page', request()->input('page.number', 1)
        );
    }

    /**
     * Store.
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function store()
    {
        $this->validate('create');

        return $this->model->create($this->getRequest());
    }

    /**
     * Show.
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function show($idd)
    {
        return $this->model->find($idd);
    }

    /**
     * Update.
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function update($idd)
    {
        $this->validate('update');

        if ($model = $this->model->find($idd)) {
            return $model->update($this->getRequest()) ? $model : false;
        }

        return false;
    }

    /**
     * Destroy.
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function destroy($idd)
    {
        $model = $this->model->find($idd);

        return $model ? $model->delete() : false;
    }

    /**
     * Get request body data->attibutes.
     *
     * @return array
     */
    private function getRequest()
    {
        $requst = app('api')->jsonBody();

        return isset($requst['attributes']) ? $requst['attributes'] : [];
    }

    /**
     * Validate Form.
     *
     * @param string $action
     *
     * @return void
     */
    private function validate($action)
    {
        $validator = validator()->make($this->getRequest(), $this->model->getRule($action));
        $errors    = [];
        foreach ($validator->errors()->toArray() as $field => $err) {
            array_push($errors, [
                //'code'   => 0, # TO-DO: report valid json api error code base on validation error.
                'source' => ['pointer' => $field],
                'title'  => trans('jsonapi::errors.invalid_attribute.title'),
                'detail' => implode(' ', $err),
            ]);
        }

        if (! empty($errors)) {
            throw (new InvalidAttributeException('invalid_attribute', 403))->withErrors($errors);
        }
    }
}
