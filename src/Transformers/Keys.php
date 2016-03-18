<?php

namespace Askedio\Laravel5ApiController\Transformers;

use Askedio\Laravel5ApiController\Helpers\Api;

/**
 * Class Keys.
 *
 * Assists in transforming result keys to json api spec
 */
class Keys
{
    /**
      * @link http://jsonapi.org/format/#document-member-names-reserved-characters
      *
      * @var array
      */
     protected $forbiddenMemberNameCharacters = [
         '+',
         ',',
         '.',
         '[',
         ']',
         '!',
         '"',
         '#',
         '$',
         '%',
         '&',
         '\'',
         '(',
         ')',
         '*',
         '/',
         ':',
         ';',
         '<',
         '=',
         '>',
         '?',
         '@',
         '\\',
         '^',
         '`',
         '{',
         '|',
         '}',
         '~',
     ];
     /**
      * @link http://jsonapi.org/format/#document-member-names-allowed-characters
      *
      * @var array
      */
     protected $forbiddenAsFirstOrLastCharacter = [
         '-',
         '_',
         ' ',
     ];

    public function transform($array)
    {
        $results = [];
        foreach ($array as $key => $value) {
            $results[$this->convert($key)] = is_array($value) ? $this->transform($value) : $value;
        }

        return $results;
    }

    private function convert($key)
    {
        if (!is_string($key)) {
            return $key;
        }

        $firstLast = implode('', $this->forbiddenAsFirstOrLastCharacter);

        return str_replace($this->forbiddenMemberNameCharacters, '', ltrim(rtrim($key, $firstLast), $firstLast));
    }
}
