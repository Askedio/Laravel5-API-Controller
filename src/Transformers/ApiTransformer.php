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


    private $object;



    public function transform($object)
    {
      $this->object = $object;

      $results = $this->isPaginator() ? $this->transformPaginator() : $this->transformObject();

      return (new KeysTransformer())->transform($results);
    }


private function relations($includes, $object)
{


  $relations = [];
  foreach($includes as $inc){
    $relations[$inc['type']]['data'] = ['id' => $inc['attributes']['id'], 'type' => $inc['type']];
  }

  return $relations;


}

private function transformation($object, $single=false){
  $includes = $this->objectIncludes($object);

  $ddd = $single ? ['data' => $this->item($object) ] : $this->item($object);
  $data = array_merge($ddd, ['relationships' => $this->relations($includes, $object)]);

  return array_merge(
   $data,
  ['included' =>  $includes]
  );

}

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

     private function transformPaginator()
     {
       $results = array_map(function($object){
         return $this->transformation($object);
       }, $this->object->all());
       return  array_merge(['data' => $results], $this->getPaginationMeta());
     }


     private function transformObject()
     {
       return $this->transformation($this->object, true);
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
          'type'       => $object->getTable(),
          'id'         => $object->$pimaryId,
          'attributes' => $object->filterAndTransform(),
        ];
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
        return [
          'meta'  => [
            'total'        => $this->object->total(),
            'currentPage'  => $this->object->currentPage(),
            'perPage'      => $this->object->perPage(),
            'hasMorePages' => $this->object->hasMorePages(),
            'hasPages'     => $this->object->hasPages(),
          ],
          'links' => [
            'self'  => $this->object->url($this->object->currentPage()),
            'first' => $this->object->url(1),
            'last'  => $this->object->url($this->object->lastPage()),
            'next'  => $this->object->nextPageUrl(),
            'prev'  => $this->object->previousPageUrl(),
          ],
        ];
    }
}
