<?php

namespace Askedio\Laravel5ApiController\Transformers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class Transformer.
 *
 * Assists in transforming models
 */
class Transformer
{
    private $type;
    private $id;

    private static function render($content)
    {
        $id = $content->getId();

        return [
                'type'       => strtolower(class_basename($content)),
                'id'         => $content->$$id,
                'attributes' => $content->transform($content),
          ];
    }

    /**
     * Transforms the modals having transform method.
     *
     * @param $content
     *
     * @return array
     */
    public static function convert($content)
    {
        if (is_object($content) && self::isTransformable($content)) {
            $content = ['data' => self::render($content)];
        } elseif ($content instanceof LengthAwarePaginator) {
            $content = array_merge([
            'data' => self::transformObjects($content->items()),
          ], self::getPaginationMeta($content));
        }

        return is_array($content) ? array_merge($content, [
          'jsonapi' => ['version' => '1.0'],
        ]) : $content;
    }

    /**
     * Transforms an array of objects using the objects transform method.
     *
     * @param $toTransform
     *
     * @return array
     */
    private static function transformObjects($toTransform)
    {
        $transformed = [];
        foreach ($toTransform as $key => $item) {
            $transformed[$key] = self::isTransformable($item) ? self::render($item) : $item;
        }

        return $transformed;
    }

    /**
     * Checks whether the object is transformable or not.
     *
     * @param $item
     *
     * @return bool
     */
    private static function isTransformable($item)
    {
        return is_object($item) && method_exists($item, 'transform');
    }

    /**
     * Gets the pagination meta data. Assumes that a paginator
     * instance is passed \Illuminate\Pagination\LengthAwarePaginator.
     *
     * @param $paginator
     *
     * @return array
     */
    private static function getPaginationMeta($paginator)
    {
        return [
          'meta'  => [
            'current_page'   => $paginator->currentPage(),
            'total_pages'    => $paginator->total(),
            'per_page'       => $paginator->perPage(),
            'has_more_pages' => $paginator->hasMorePages(),
            'has_pages'      => $paginator->hasPages(),
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
