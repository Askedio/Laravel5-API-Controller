<?php

namespace Askedio\Laravel5ApiController\Helpers;

/**
 * Intended to build a collection of objects to be validated and transformed
 * Base model is already loaded
 * Load each included module - recurisvely load sub models
 * Build collection of all possible model options
 * - profiles, profiles.addresses.
 */
class ApiObjects
{
    /** @var collection */
    private $relations;

    /** @var collection */
    private $fillables;

    /** @var object */
    private $baseObject;

    /** @var collection */
    private $includes;

    /** @var collection */
    private $columns;

/* will use some day
    public function getRelations()
    {
      return $this->relations;
    }
*/

    public function getFillables()
    {
        return $this->fillables;
    }

    public function getIncludes()
    {
        return $this->includes;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function __construct($object)
    {
        $this->baseObject = $object;
        $this->fillables = collect([]);
        $this->includes = collect([]);
        $this->columns = collect([]);
        $this->relations = collect([$this->includes($object)]);
    }

  /**
   * Itterate over object, build a relations, fillable and includes collection.
   *
   * @param  model $object the model to iterate over
   *
   * @return array
   */
  private function includes($object)
  {
      $primaryId = $object->getId();
      $fillable = $object->getFillable();
      $includes = $object->getIncludes();
      $table = $object->getTable();
      $columns = $object->columns();

      $results[$table] = [];

      if (!empty($includes)) {
          foreach ($includes as $include) {
              $results[$table] = [
               'primaryId' => $primaryId,
               'fillable'  => $fillable,
               'columns'   => $columns,
               'includes'  => $this->includes(new $include()),
             ];
          }
      }

      $this->fillables->put($table, $fillable);
      $this->includes->push($table, $table);
      $this->columns->put($table, $columns);

      return $results;
  }
}
