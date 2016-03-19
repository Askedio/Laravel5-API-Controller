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

    /**
     * Build all collections.
     *
     * @param object $object The default model object
     */
    public function __construct($object)
    {
        $this->baseObject = $object;
        $this->fillables = collect([]);
        $this->includes = collect([]);
        $this->columns = collect([]);
        $this->relations = collect($this->includes($object));
    }

    /**
     * Return a collection of all fillable items.
     *
     * @return collection
     */
    public function getFillables()
    {
        return $this->fillables;
    }

    /**
     * Return a collection of all includes.
     *
     * @return collection
     */
    public function getIncludes()
    {
        return $this->includes;
    }

    /**
     * Return a collection of all columns.
     *
     * @return collection
     */
    public function getColumns()
    {
        return $this->columns;
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

              'object'   => $object,
              'includes' => $this->includes(new $include()),
             ];
          }
      }

      $this->fillables->put($table, $fillable);
      $this->includes->push($table, $table);
      $this->columns->put($table, $columns);

      return $results;
  }
}
