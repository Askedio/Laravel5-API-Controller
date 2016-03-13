<?php

namespace Askedio\Laravel5ApiController\Helpers;

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
        $this->model->validateApi();
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

        return $results->paginate((request()->input('limit') ?: '10'));
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
        }

        return $this->model->create($this->getRequest());
    }

    /**
     * Show.
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function show($_id)
    {
        return $this->model->find($_id);
    }

    /**
     * Update.
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function update($_id)
    {
        if ($errors = $this->validate('update')) {
            return ['errors' => $errors];
        }

        if ($_model = $this->model->find($_id)) {
            return $_model->update($this->getRequest()) ? $_model : false;
        }

        return false;
    }

    /**
     * Destroy.
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function destroy($_id)
    {
        $_model = $this->model->find($_id);

        return $_model ? $_model->delete() : false;
    }

    /**
     * Clean Request Fields.
     *
     * @return array
     */
    private function getRequest()
    {
        return request()->json()->all();
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
        $validator = validator()->make(request()->json()->all(), $this->model->getRule($action));
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
