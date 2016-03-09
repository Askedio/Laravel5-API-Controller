<?php

namespace Askedio\Laravel5ApiController\Traits;

trait ApiTrait
{
    /**
     * The validation rules assigned in model
     * 
     * @var string
     * @return array
     */
    public function getRule($rule)
    {
        return isset($this->rules[$rule]) ? $this->rules[$rule] : [];
    }

    /**
     * The id_field defined in the model
     *
     * @return string
     */
    public function getId()
    {
        return isset($this->id_field) ? $this->id_field : 'id';
    }

    /**
     * Set order/sort as per json spec
     *
     * @return object
     */
    public function scopesetSort($query, $sort)
    {
        if (!empty($sort) && is_string($sort)) {
            $members = explode(',', $sort);
            if (!empty($members)) {
                foreach ($members as $field) {
                    $query->orderBy(ltrim($field, '-'), ('-' === $field[0]) ? 'DESC' : 'ASC');
                }
            }
        }

        return $query;
    }

}
