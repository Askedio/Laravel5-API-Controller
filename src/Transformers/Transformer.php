<?php

namespace Askedio\Laravel5ApiController\Transformers;

use Askedio\Laravel5ApiController\Exceptions\BadRequestException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Askedio\Laravel5ApiController\Helpers\ApiHelper;
use Request;

/**
 * Class Transformer.
 *
 * Assists in transforming models
 */
class Transformer
{
    private static $fields;

    private static function render($content)
    {
        $id = $content->getId();

        return [
          'type'       => strtolower(class_basename($content)),
          'id'         => $content->$$id,
          'attributes' => self::filter($content),
        ];
    }

    private static function filter($content)
    {
        if (!is_array(Request::input('fields'))) {
            return $content->transform($content);
        }

        $_results = [];
        $_key = strtolower(class_basename($content));
        $_content = self::isTransformable($content) ? $content->transform($content) : $content;
        if (is_array(self::$fields[$_key])) {
            foreach (self::$fields[$_key] as $filter) {
                if (isset($_content[$filter])) {
                    $_results[$filter] = $_content[$filter];
                }
            }
        }

        return $_results;
    }

    public static function fields($model)
    {
        if (is_array(Request::input('fields'))) {
            $_fields = array_filter(Request::input('fields'));
            $_results = [];
            foreach ($_fields as $type => &$members) {
                $members = array_map('trim', explode(',', $members));
                foreach ($members as $member) {
                    $_results[$type][] = $member;
                }
            }

            return $_results;
        } else {
            return $model;
        }
    }

    private static function includes($content)
    {
      $_results = [];

        foreach (ApiHelper::includes() as $relationship) {
          
            if (is_object($content->$relationship)) {
                foreach ($content->$relationship as $sub) {
                    $_results[] = self::render($sub);
                }
            }
        }

        return $_results;
    }

    private static function gen($model)
    {
        if (is_object($model)) {
            $_results = [];

            if (Request::input('include') && $incs = self::includes($model)) {
                $_results['relationships'] = [];
                $_results['included'] = [];
                foreach ($incs as $i => $include) {
                    if (!isset($_results['relationships'][$include['type']])) {
                        $_results['relationships'][$include['type']]['data'] = [];
                    }
                    array_push($_results['relationships'][$include['type']]['data'], ['id' => $include['id'], 'type' => $include['type']]);
                    array_push($_results['included'], $include);
                }
            }

            return $_results;
        }
    }

    public static function convert($model)
    {
        self::$fields = self::fields($model);

        $_results = [
                 'jsonapi' => [
                   'version' => config('jsonapi.version', '1.0'),
                 ],
               ];

        if (is_object($model)) {
            if (!$model instanceof LengthAwarePaginator) {
                $_results = array_merge(
                [
                  'data'  => self::render($model),
                ],
                self::gen($model),
                $_results
              );
            } elseif ($model instanceof LengthAwarePaginator) {
                $_results = array_merge(
               [
                  'data' => self::transformObjects($model->items()),
                ],
                self::getPaginationMeta($model),
                $_results
              );
            }
        }

        return $_results;
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
            $transformed[$key] = self::isTransformable($item) ? array_merge(self::render($item), self::gen($item)) : $item;
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
