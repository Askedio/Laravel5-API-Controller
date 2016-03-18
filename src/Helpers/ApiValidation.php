<?php

namespace Askedio\Laravel5ApiController\Helpers;

use Askedio\Laravel5ApiController\Exceptions\BadRequestException;

/**
 * Intended to validate the ApiObjects collection.
 */
class ApiValidation
{
    public function __construct($settings)
    {
        $this->validateIncludes($settings['includes']);
        $this->validateFields($settings['columns'], $settings['includes']);
        $this->validateRequests($settings['fillable']);
    }

    public function validateRequests($fillable)
    {
        if (!request()->isMethod('post') && !request()->isMethod('patch')) {
            return;
        }

        $_request = request()->json()->all();
        $errors = array_diff(array_keys($_request), $fillable->flatten()->all());
        if (!empty($errors)) {
            throw (new BadRequestException('invalid_filter'))->withDetails($errors);
        }
    }

  /**
   * Check if includes get variable is valid.
   *
   * @return void
   */
  public function validateIncludes($allowed)
  {
      $includes = app('api')->includes();

      $errors = array_diff($includes->all(), $allowed->all());

      if (!empty($errors)) {
          throw (new BadRequestException('invalid_include'))->withDetails($errors);
      }
  }

  /**
   * Validate fields belong.
   *
   * @return array
   */
  public function validateFields($columns, $includes)
  {
      $fields = app('api')->fields();

      $errors = array_diff($fields->keys()->all(), $includes->all());
      if (!empty($errors)) {
          throw (new BadRequestException('invalid_filter'))->withDetails($errors);
      }

      $errors = $fields->map(function ($item, $key) use ($columns) {
        return array_diff($item, $columns->get($key));
      })->flatten()->all();

      if (!empty($errors)) {
          throw (new BadRequestException('invalid_filter'))->withDetails($errors);
      }
  }
}
