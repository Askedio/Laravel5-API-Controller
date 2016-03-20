<?php

namespace Askedio\Laravel5ApiController\Transformers;

use Askedio\Laravel5ApiController\Helpers\Api;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class Transformer.
 *
 * Assists in filtering and transforming model
 */
class ApiTransformer
{
    /** @var object */
    private $object;

    /**
     * Detect the type of object, transform and return a KeysTransformerd results.
     *
     * @param object $object
     *
     * @return KeysTransformer
     */
    public function transform($object)
    {
        $this->object = $object;

        $results = $this->isPaginator() ? $this->transformPaginator() : $this->transformObject();

        return array_filter((new KeysTransformer())->transform($results));
    }

    /**
     * Transform Pagination.
     *
     * @return array
     */
    private function transformPaginator()
    {
        $results = array_map(function ($object) {
            return $this->transformation($object, false);
        }, $this->object->all());

        return array_merge(['data' => $results], $this->getPaginationMeta());
    }

    /**
     * Transform objects.
     *
     * @return transformation
     */
    private function transformObject()
    {
        return $this->transformation($this->object, true);
    }

    /**
     * Build the transformed results.
     *
     * @param object $object
     * @param bool   $single
     *
     * @return array
     */
    private function transformation($object, $single)
    {
        $includes = $this->objectIncludes($object);

        $item = $single ? ['data' => $this->item($object)] : $this->item($object);
        $data = array_merge($item, ['relationships' => $this->relations($includes)]);

        return array_merge(
            $data,
            ['included' => $includes],
            $single ? ['links' => ['self' => request()->url()]] : []
        );
    }

    /**
     * Build a list of includes for this object.
     *
     * @param object $object
     *
     * @return array
     */
    private function objectIncludes($object)
    {
        $results = [];

        foreach (app('api')->includes() as $include) {
            if (is_object($object->$include)) {
                foreach ($object->$include as $included) {
                    $results[] = $this->item($included);
                }
            }
        }

        return $results;
    }

    /**
     * Build json api style results per item.
     *
     * @param $object
     *
     * @return array
     */
    private function item($object)
    {
        $pimaryId = $object->getId();

        return [
            'type'       => $object->getTable(),
            'id'         => $object->$pimaryId,
            'attributes' => $object->filterAndTransform(),
        ];
    }

    /**
     * Get relations for the included items.
     *
     * @param [type] $includes [description]
     * @param [type] $object   [description]
     *
     * @return [type] [description]
     */
    private function relations($includes)
    {
        return array_map(function ($inc) {
            return [$inc['type'] => ['data' => ['id' => $inc['attributes']['id'], 'type' => $inc['type']]]];
        }, $includes);
    }

    /**
     * @param $object
     *
     * @return bool
     */
    private function isPaginator()
    {
        return $this->object instanceof LengthAwarePaginator;
    }

    /**
     * Gets the pagination meta data. Assumes that a paginator
     * instance is passed \Illuminate\Pagination\LengthAwarePaginator.
     *
     * @param $paginator
     *
     * @return array
     */
    private function getPaginationMeta()
    {
        $object = $this->object;

        return [
            'meta'  => [
                'total'        => $object->total(),
                'currentPage'  => $object->currentPage(),
                'perPage'      => $object->perPage(),
                'hasMorePages' => $object->hasMorePages(),
                'hasPages'     => $object->hasPages(),
            ],
            'links' => [
                'self'  => $object->url($object->currentPage()),
                'first' => $object->url(1),
                'last'  => $object->url($object->lastPage()),
                'next'  => $object->nextPageUrl(),
                'prev'  => $object->previousPageUrl(),
            ],
        ];
    }
}
