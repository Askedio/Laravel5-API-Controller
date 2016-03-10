<?php

namespace Askedio\Laravel5ApiController\Transformers;

use Askedio\Laravel5ApiController\Exceptions\BadRequestException;
use Askedio\Laravel5ApiController\Helpers\ApiHelper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class Transformer.
 *
 * Assists in filtering and transforming model
 */

class Transformer
{
    private static $object;

    public static function render($object)
    {
        self::$object = $object;
        return array_merge(self::objectOrPage($object), self::jsonHeader());

    }

    private static function objectOrPage($object){
        $_results = [];
        if (is_object($object)) {
            if (!self::isPaginator()) {
                $_data     = self::item($object);
                $_include  = self::includes($object);
            } elseif(self::isPaginator()) {
                $_data     = self::transformObjects($object->items());
                $_include  = self::getPaginationMeta($object);
            }

            $_results = array_merge(['data' => $_data], $_include);
        }
        return $_results;

    }

    private static function includes($object)
    {
        if (is_object($object)) {
            $_results = [];
            $incs = self::getIncludes($object);
            if (!empty($incs)) {
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

    private static function getIncludes($object)
    {
        $_results = [];

        foreach (ApiHelper::includes() as $include) {
            if (is_object($object->$include)) {
                foreach ($object->$include as $included) {
                    $_results[] = self::item($included);
                }
            }
        }

        return $_results;
    }









    /**
     *
     *
     * @param $object
     *
     * @return array
     */
    private static function transformObjects($object)
    {
        $_results = [];
        foreach ($object as $key => $item) {
            $_results[$key] = array_merge(self::item($item), self::includes($item)) ;
        }

        return $_results;
    }

















    private static function isPaginator()
    {
      return self::$object instanceof LengthAwarePaginator;
    }


    private static function item($content)
    {
        $id = $content->getId();
        return [
          'type'       => strtolower(class_basename($content)),
          'id'         => $content->$$id,
          'attributes' => $content->filterAndTransform(),
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




    private static function jsonHeader()
    {
        return [
          'jsonapi' => [
            'version' => config('jsonapi.version', '1.0'),
          ],
        ];
    }






}
