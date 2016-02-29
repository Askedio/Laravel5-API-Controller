<?php

namespace Askedio\Laravel5ApiController\Http\Controllers;

use Askedio\Laravel5ApiController\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as Controller;
use Validator;

class BaseController extends Controller
{
    private $_modal;
    private $request;

    public function __construct(Request $request)
    {
        $this->_modal = new $this->modal();
        $this->request = $request;
    }

    public function index(Request $request)
    {
        $results = $this->_modal->orderBy(($request->input('order') ?: 'id'),($request->input('direction') ?: 'DESC'));
        if($request->input('search')) $results->search($request->input('search'));

        return ApiHelper::success($results->paginate(($request->input('limit') ?: '10')));
    }

    public function store()
    {
        if ($errors = $this->validate('create')) {
            return $errors;
        } else {
            $_new = $this->_modal->create($this->cleanRequest());

            return $_new
              ? ApiHelper::success($_new)
              : ApiHelper::error(['Unable to create modal entry.']);
        }
    }

    public function show($id)
    {
        return $this->_modal->findOrfail($id);
    }

    public function update($id)
    {
        return $this->validate('update') ?:
          ($this->_modal->findOrfail($id)->update($this->cleanRequest())
              ? ApiHelper::success($this->_modal->find($id))
              : ApiHelper::error(['Unable to update '.$id.'.'])
          );
    }

    public function destroy($id)
    {
        return $this->_modal->findOrfail($id)->delete()
              ? ApiHelper::success([$id.' deleted.'])
              : ApiHelper::error(['Unable to delete '.$id.'.']);
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

        return $validator->fails() ? ApiHelper::error($validator->errors()->all()) : false;
    }
}
