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
        return $this->objectOrPage($object);
    }

    /**
     * @param $object
     *
     * @return array
     */
    private function objectOrPage($object)
    {
        $_results = [];
        if (is_object($object)) {
            if (!$this->isPaginator($object)) {
                $_data = $this->item($object);
                $_include = $this->includes($object);
            } elseif ($this->isPaginator($object)) {
                $_data = $this->transformObjects($object->items());
                $_include = $this->getPaginationMeta($object);
            }

            $_results = array_merge(['data' => $_data], $_include);
        }

        return $_results;
    }

    /**
     * @param $object
     *
     * @return array
     */
    private function includes($object)
    {
        $_results = [];
        if (!is_object($object)) {
            return $_results;
        }

        $incs = $this->getIncludes($object);

        if (empty($incs)) {
            return $_results;
        }

        $_results['relationships'] = [];
        $_results['included'] = [];
        foreach (array_values($incs) as $include) {
            if (!isset($_results['relationships'][$include['type']])) {
                $_results['relationships'][$include['type']]['data'] = [];
            }
            array_push($_results['relationships'][$include['type']]['data'], ['id' => $include['id'], 'type' => $include['type']]);
            array_push($_results['included'], $include);
        }

        return $_results;
    }

    /**
     * @param $object
     *
     * @return array
     */
    private function getIncludes($object)
    {
        $_results = [];

        foreach (app('api')->includes() as $include) {
            if (is_object($object->$include)) {
                foreach ($object->$include as $included) {
                    $included->validateApi();
                    $_results[] = $this->item($included);
                }
            }
        }

        return $_results;
    }

    /**
     * @param $object
     *
     * @return array
     */
    private function transformObjects($object)
    {
        $_results = [];
        foreach ($object as $key => $item) {
            $_results[$key] = array_merge($this->item($item), $this->includes($item));
        }

        return $_results;
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
