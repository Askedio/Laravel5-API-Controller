<?php

namespace Askedio\Laravel5ApiController\Traits;

use Askedio\Laravel5ApiController\Helpers\ApiHelper;
use Askedio\Laravel5ApiController\Helpers\ControllerHelper;
use Illuminate\Http\Request;

trait ControllerTrait
{
    private $_modal;
    private $request;
    private $helper;

    public function __construct(Request $request)
    {
        $this->_modal = new $this->modal();
        $this->request = $request;

        ApiHelper::setModal(class_basename($this->_modal));

        // no aliases/facades/static..
        $this->helper = new ControllerHelper($request, $this->_modal);
    }

    public function index(Request $request)
    {
        $_results = $this->helper->renderIndex();

        return ApiHelper::success($_results);
    }

    public function store()
    {
        $_results = $this->helper->store();
        if (isset($_results['errors'])) {
            return ApiHelper::error($_results['errors']);
        } else {
            return $_results
              ? ApiHelper::success($_results)
              : ApiHelper::throwException(500);
        }
    }

    public function show($id)
    {
        $_results = $this->helper->show($id);

        return $_results
          ? ApiHelper::success($_results)
          : ApiHelper::throwException(404);
    }

    public function update($id)
    {
        $_results = $this->helper->update($id);
        if (isset($_results['errors'])) {
            return ApiHelper::error($_results['errors']);
        } else {
            return $_results
              ? ApiHelper::success($_results)
              : ApiHelper::throwException(500);
        }
    }

    public function destroy($id)
    {
        $_data = $this->helper->show($id);
        $_results = $this->helper->destroy($id);

        return $_results
              ? ApiHelper::success($_data)
              : ApiHelper::throwException(500);
    }
}
