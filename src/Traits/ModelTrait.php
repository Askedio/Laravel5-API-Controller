<?php

namespace Askedio\Laravel5ApiController\Traits;

use Askedio\Laravel5ApiController\Exceptions\BadRequestException;
use Askedio\Laravel5ApiController\Helpers\Api;
use Askedio\Laravel5ApiController\Helpers\ApiObjects;
use DB;

trait ModelTrait
{
    public function getIncludes()
    {
        return isset($this->includes) ? $this->includes : [];
    }

    private $objects;

    public function getObjects()
    {
        if (!$this->objects) {
            $this->objects = new ApiObjects($this);
        }

        return  $this->objects;
    }

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
        if (empty($sort) ||  !is_string($sort) || empty($sorted = explode(',', $sort))) {
            return $query;
        }

        $columns = $this->columns();

        $errors = array_filter(array_diff(array_map(function ($string) {
          return ltrim($string, '-');
        }, $sorted), $columns));

        if (!empty($errors)) {
            throw (new BadRequestException('invalid_sort'))->withDetails([[strtolower(class_basename($this)), implode(' ', $errors)]]);
        }

        array_map(function ($column) use ($query) {
          return $query->orderBy(ltrim($column, '-'), ('-' === $column[0]) ? 'DESC' : 'ASC');
        }, $sorted);

        return $query;
    }

    /**
     * Filter results based on filter get variable and transform them if enabled.
     *
     * @return array
     */
    public function scopefilterAndTransform()
    {
        $results = $this->toArray();
        $fields = app('api')->fields();

        $key = $this->getTable();

        if ($fields->isEmpty() || !$fields->has($key)) {
            return $results;
        }

        $results = [];

        $columns = $this->columns();

        foreach ($fields[$key] as $filter) {
            if (in_array($filter, $columns)) {
                $content = $this->isTransformable($this) ? $this->transform($this) : $results;
                $results[$filter] = $content[$filter];
            }
        }

        return $results;
    }

    /**
     * List of columns related to this Model, Cached.
     *
     * @return array
     */
    private $cols;

    public function columns()
    {
        if (!$this->cols) {
            $this->cols = DB::connection()->getSchemaBuilder()->getColumnListing($this->getTable());
        }

        return $this->cols;
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
