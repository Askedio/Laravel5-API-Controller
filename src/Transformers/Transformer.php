<?php

namespace Askedio\Laravel5ApiController\Transformers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Request;

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

    private static function includes($content)
    {
        $include = Request::input('include');
        $_results = [];
        if (is_string($include)) {
            $includeNames = explode(',', $include);
            foreach ($includeNames as $relationship) {
                if (is_object($content->$relationship)) {
                    foreach ($content->$relationship as $sub) {
                        $_results[] = self::render($sub);
                    }
                }
            }
        }

        return $_results;
    }

    /**
     * Transforms the modals having transform method.
     *
     * @param $content
     *
     * @return array
     */
    public static function convert($model)
    {
        if (is_object($model) && self::isTransformable($model)) {
            $content = [
              'data'  => self::render($model),
              /* need to go into model 'links' => [
                  'self' => Request::url(),
                  // 'related' => .. so need a function
              ],*/
            ];

            if ($incs = self::includes($model)) {
                $content['included'] = [];
                foreach ($incs as $i => $include) {
                    array_push($content['included'], $include);
                }
            }
        } elseif ($model instanceof LengthAwarePaginator) {
            $content = array_merge(
              [
                'data' => self::transformObjects($model->items()),
              ],
              self::getPaginationMeta($model)
            );
        }

        return is_array($content) ? array_merge($content,
               [
                 'jsonapi' => [
                   'version' => config('jsonapi.version', '1.0'),
                 ],
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
