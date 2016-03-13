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
     * Remove left dash from sort string.
     *
     * @param string $string
     *
     * @return string
     */
    private function removeSortDash($string)
    {
        return ltrim($string, '-');
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
        if (empty($sort) ||  !is_string($sort) || empty($_sorted = explode(',', $sort))) {
            return $query;
        }

        $_columns = $this->columns();

        $_errors = array_diff(array_map(['self', 'removeSortDash'], $_sorted), $_columns);

        if (!empty($_errors)) {
            $exception = new BadRequestException('invalid_include');
            throw $exception->withDetails([[strtolower(class_basename($this)), implode(' ', $_errors)]]);
        }

        foreach ($_sorted as $column) {
            $query->orderBy(ltrim($column, '-'), ('-' === $column[0]) ? 'DESC' : 'ASC');
        }

        return $query;
    }

    /**
     * Check if includes get variable is valid.
     *
     * @return void
     */
    public function scopevalidateIncludes()
    {
        $_allowed = $this->includes ?: [];
        $_includes = app('api')->includes();

        $_errors = array_diff($_includes, $_allowed);
        if (!empty($_errors)) {
            $exception = new BadRequestException('invalid_include');
            throw $exception->withDetails([[strtolower(class_basename($this)), implode(' ', $_errors)]]);
        }
    }

    /**
     * Validate fields belong.
     *
     * @return array
     */
    public function scopevalidateFields()
    {
        $_fields = app('api')->fields();
        $_key = strtolower(class_basename($this));

        if (empty($_fields)) {
            return $this;
        }

        $_errors = array_diff(array_keys($_fields), array_merge([$_key], $this->includes));
        if (!empty($_errors)) {
            $exception = new BadRequestException('invalid_filter');
            throw $exception->withDetails([[$_key, implode(' ', $_errors)]]);
        }

        if (array_key_exists($_key, $_fields)) {
            $_columns = $this->columns();

            foreach ($_fields[$_key] as $filter) {
                if (!in_array($filter, $_columns)) {
                    $exception = new BadRequestException('invalid_filter');
                    throw $exception->withDetails([[$_key, $filter]]);
                }
            }
        }
    }

    /**
     * Filter results based on filter get variable and transform them if enabled.
     *
     * @return array
     */
    public function scopefilterAndTransform()
    {
        // badd too, loops each, this was intended as a post check
        $_fields = app('api')->fields();
        $_key = strtolower(class_basename($this));
        if (empty($_fields) || !isset($_fields[$_key])) {
            return $this;
        }

        $_results = [];

        $_columns = $this->columns();

        foreach ($_fields[$_key] as $filter) {
            if (in_array($filter, $_columns)) {
                $_content = $this->isTransformable($this) ? $this->transform($this) : $this;
                $_results[$filter] = $_content[$filter];
            }
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
        return app('cache')->remember('columns-'.$this->getTable(), 5, function () {
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
