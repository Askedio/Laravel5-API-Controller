<?php

namespace Askedio\Laravel5ApiController\Traits;

use Askedio\Laravel5ApiController\Exceptions\BadRequestException;
use Askedio\Laravel5ApiController\Helpers\Api;
use DB;

trait ApiTrait
{
    /**
     * The validation rules assigned in model.
     *
     * @var string
     *
     * @return array
     */
    public function getRule($rule)
    {
        return isset($this->rules[$rule]) ? $this->rules[$rule] : [];
    }

    /**
     * The id_field defined in the model.
     *
     * @return string
     */
    public function getId()
    {
        return isset($this->primaryKey) ? $this->primaryKey : 'id';
    }

        /**
         * Return if Model has searchable flag.
         *
         * @return bool
         */
        public function isSearchable()
        {
            return isset($this->searchable);
        }

    /**
     * Set order/sort as per json spec.
     *
     * @param string $query
     * @param string $sort
     *
     * @return object
     */
    public function scopesetSort($query, $sort)
    {
        if (!empty($sort) && is_string($sort)) {
            $members = explode(',', $sort);
            if (!empty($members)) {
                $_columns = $this->columns();
                foreach ($members as $column) {
                    if (!in_array(ltrim($column, '-'), $_columns)) {
                        $exception = new BadRequestException('invalid_sort');
                        throw $exception->withDetails([[strtolower(class_basename($this)), ltrim($column, '-')]]);
                    }
                    $query->orderBy(ltrim($column, '-'), ('-' === $column[0]) ? 'DESC' : 'ASC');
                }
            }
        }

        return $query;
    }

    /**
     * Check if includes get variable is valid.
     *
     * @return void
     */
    public function scopecheckIncludes()
    {
        $_allowed = $this->includes ?: [];
        $_includes = app('api')->includes();
        if (empty($_includes) || empty($_allowed)) {
            return false;
        }
        foreach ($_includes as $include) {
            if (!in_array($include, $_allowed)) {
                $exception = new BadRequestException('invalid_include');
                throw $exception->withDetails([strtolower(class_basename($this)), $include]);
            }
        }
    }

    /**
     * List of fields from input.
     *
     * @return array
     */
    public function fields()
    {
  // bad, called for each row - just need it once..
        $_results = [];
        foreach (array_filter(request()->input('fields', [])) as $type => $members) {
            foreach (explode(',', $members) as $member) {
                $_results[$type][] = $member;
            }
        }

        return $_results;
    }

    /**
     * Filter results based on filter get variable and transform them if enabled.
     *
     * @return array
     */
    public function scopefilterAndTransform()
    {
  // badd too, loops each, this was intended as a post check
        $_fields = $this->fields();
        if (empty($_fields)) {
            return $this;
        }

        $_results = [];
        $_errors = [];
        $_key = strtolower(class_basename($this));
        $_columns = $this->columns();
        $_content = $this->isTransformable($this) ? $this->transform($this) : $this;


        foreach ($_fields[$_key] as $filter) {

            if (in_array($filter, $_columns)) {
                $_results[$filter] = $_content[$filter];
            }
            if (!in_array($filter, $_columns)) {
                array_push($_errors, [$_key, $filter]);
            }
        }
        if (!empty($_errors)) {
            $exception = new BadRequestException('invalid_filter');
            throw $exception->withDetails($_errors);
        }

        return $_results;


    }

    /**
     * List of columns related to this Model, Cached.
     *
     * @return array
     */
    private function columns()
    {
        return app('cache')->rememberForever('columns-'.$this->getTable(), function () {
              return DB::connection()->getSchemaBuilder()->getColumnListing($this->getTable());
      });
    }

    /**
     * Checks whether the object is transformable or not.
     *
     * @param $item
     *
     * @return bool
     */
    private function isTransformable($item)
    {
        return is_object($item) && method_exists($item, 'transform');
    }
}
