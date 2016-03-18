<?php

namespace Askedio\Laravel5ApiController\Transformers;

use Askedio\Laravel5ApiController\Helpers\Api;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class Transformer.
 *
 * Assists in filtering and transforming model
 */
class Transformer
{
    /**
     * @param $object
     *
     * @return array
     */
    public function render($object)
    {
        $results = [];
        if (is_object($object)) {
            if (!$this->isPaginator($object)) {
                $_data = $this->item($object);
                $_include = $this->includes($object);
                $_include['links']['self'] = request()->url();
            } elseif ($this->isPaginator($object)) {
                $_data = $this->transformObjects($object->items());
                $_include = $this->getPaginationMeta($object);
            }

            $results = array_merge(['data' => $_data], $_include);
        }

        return (new Keys())->transform($results);
    }

    /**
     * @param $object
     *
     * @return array
     */
    private function includes($object)
    {
        $results = [];

        $includes = $this->getIncludes($object);

        if (empty($includes)) {
            return $results;
        }

        $results['relationships'] = [];
        $results['included'] = [];

        foreach (array_values($includes) as $include) {
            if (!isset($results['relationships'][$include['type']])) {
                $results['relationships'][$include['type']]['data'] = [];
            }
            array_push($results['relationships'][$include['type']]['data'], ['id' => $include['id'], 'type' => $include['type']]);
            array_push($results['included'], $include);
        }

        return $results;
    }

    /**
     * @param $object
     *
     * @return array
     */
    private function getIncludes($object)
    {
        $results = [];

        foreach (app('api')->includes() as $include) {
            if (is_object($object->$include)) {
                foreach ($object->$include as $included) {
                    $included->validateApi();
                    $results[] = $this->item($included);
                }
            }
        }

        return $results;
    }

    /**
     * @param $object
     *
     * @return array
     */
    private function transformObjects($object)
    {
        $results = [];
        foreach ($object as $key => $item) {
            $results[$key] = array_merge($this->item($item), $this->includes($item));
        }

        return $results;
    }

    /**
     * @param $object
     *
     * @return bool
     */
    private function isPaginator($object)
    {
        return $object instanceof LengthAwarePaginator;
    }

    /**
     * @param $object
     *
     * @return array
     */
    private function item($object)
    {
        $pimaryId = $object->getId();

        return [
          'type'       => strtolower(class_basename($object)),
          'id'         => $object->$pimaryId,
          'attributes' => $object->filterAndTransform(),
        ];
    }

    /**
     * Gets the pagination meta data. Assumes that a paginator
     * instance is passed \Illuminate\Pagination\LengthAwarePaginator.
     *
     * @param $paginator
     *
     * @return array
     */
    private function getPaginationMeta($paginator)
    {
        return [
          'meta'  => [
            'total'        => $paginator->total(),
            'currentPage'  => $paginator->currentPage(),
            'perPage'      => $paginator->perPage(),
            'hasMorePages' => $paginator->hasMorePages(),
            'hasPages'     => $paginator->hasPages(),
          ],
          'links' => [
            'self'  => $paginator->url($paginator->currentPage()),
            'first' => $paginator->url(1),
            'last'  => $paginator->url($paginator->lastPage()),
            'next'  => $paginator->nextPageUrl(),
            'prev'  => $paginator->previousPageUrl(),
          ],
        ];
    }
}
