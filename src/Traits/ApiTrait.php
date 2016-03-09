<?php

namespace Askedio\Laravel5ApiController\Traits;

trait ApiTrait
{
    public function getRule($rule)
    {
        return $this->rules[$rule];
    }

    public function getId()
    {
        return $this->id ?: 'id';
    }

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

    public function scopesetFields($query, $fields)
    {
        if (!empty($fields)) {
            $fields = array_filter($fields);
            foreach ($fields as $type => &$members) {
                $members = explode(',', $members);
                $members = array_map('trim', $members);
                foreach ($members as $member) {
                    // $object->addField($type, $member);
                }
            }
        }

        return $query;
    }
}
