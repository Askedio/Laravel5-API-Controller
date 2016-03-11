<?php

namespace Askedio\Laravel5ApiController\Traits;

use Askedio\Laravel5ApiController\Exceptions\BadRequestException;
use Askedio\Laravel5ApiController\Helpers\ApiHelper;
use Schema;
use Cache;

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
     * Set order/sort as per json spec.
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
                        ApiHelper::setExceptionDetails([strtolower(class_basename($this)), ltrim($column, '-')]);
                        throw new BadRequestException('invalid_sort');
                    }
                    $query->orderBy(ltrim($column, '-'), ('-' === $column[0]) ? 'DESC' : 'ASC');
                }
            }
        }

        return $query;
    }

    public function isSearchable()
    {
        return isset($this->searchable);
    }

    public function scopecheckIncludes()
    {
        $_allowed = $this->includes ?: [];
        $_includes = ApiHelper::includes();
        if (empty($_includes) || empty($_allowed)) {
            return false;
        }
        foreach ($_includes as $include) {
            if (!in_array($include, $_allowed)) {
                ApiHelper::setExceptionDetails([strtolower(class_basename($this)), $include]);
                throw new BadRequestException('invalid_include');
            }
        }
    }

    public function scopefilterAndTransform()
    {
        $_fields = ApiHelper::fields();
        if (empty($_fields)) {
            return $this;
        }

        $_results = [];
        $_errors  = [];
        $_key = strtolower(class_basename($this));
        $_columns = $this->columns();
        $_content = $this->isTransformable($this) ? $this->transform($this) : $this;

        if (isset($_fields[$_key])) {
            foreach ($_fields[$_key] as $filter) {
                if (in_array($filter, $_columns)) {
                    $_results[$filter] = $_content[$filter];
                } else {
                  array_push($_errors, [$_key, $filter]);
                }
            }
            if(!empty($_errors)){
              ApiHelper::setExceptionDetails($_errors);
              throw new BadRequestException('invalid_filter');
            }
        } else {
            $_results = $_content;
        }

        return $_results;
    }

    private function columns()
    {
      return Cache::rememberForever('columns-'. $this->getTable(), function() {
              return Schema::getColumnListing($this->getTable());
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
