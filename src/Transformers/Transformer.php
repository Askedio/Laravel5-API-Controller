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
    static private $fields;

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
      if(!is_array(Request::input('fields'))) return $content->transform($content);

      $_results = [];
      $_key = strtolower(class_basename($content));
      $_content = $content->transform($content);
      if(is_array(self::$fields[$_key])){
       foreach(self::$fields[$_key] as $filter){
          if(isset($_content[$filter])) $_results[$filter] = $_content[$filter];
        }
      }
      return $_results;

    }

    public static function fields($model)
    {
      if(is_array(Request::input('fields'))){
        $_fields = array_filter(Request::input('fields'));
        $_results = [];
        foreach ($_fields as $type => &$members) {
            $members = array_map('trim', explode(',', $members));
            foreach ($members as $member) {
              $_results[$type][] = $member;
            }
        }
        
        return $_results;
      } else return $model;
    }

    private static function includes($content)
    {
        $include = Request::input('include');
        $_results = [];
        if (!is_string($include)) return false;

            $includeNames = explode(',', $include);
            foreach ($includeNames as $relationship) {
                if (is_object($content->$relationship)) {
                    foreach ($content->$relationship as $sub) {
                        $_results[] = self::render($sub);
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
        self::$fields = self::fields($model);

        if (is_object($model) && self::isTransformable($model)) {
            $content = [
              'data'  => self::render($model),
              /* need to go into model 'links' => [
                  'self' => Request::url(),
                  // 'related' => .. so need a function
              ],*/
            ];


            if (Request::input('include') && $incs = self::includes($model) ) {
                $content['relationships'] = [];
                $content['included'] = [];
                foreach ($incs as $i => $include) {
                  if(!isset($content['relationships'][$include['type']])) $content['relationships'][$include['type']]['data'] = [];
                    array_push($content['relationships'][$include['type']]['data'],  ['id' => $include['id'], 'type' => $include['type']]);
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
