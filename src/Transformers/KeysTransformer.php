<?php

namespace Askedio\Laravel5ApiController\Transformers;

use Askedio\Laravel5ApiController\Helpers\Api;

/**
 * Class Keys.
 *
 * Assists in transforming result keys to json api spec
 */
class KeysTransformer
{
    /**
     * @link http://jsonapi.org/format/#document-member-names-reserved-characters
     *
     * @var array
     */
    protected $forbiddenCharacters = [
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
    protected $forbiddenFirstOrLast = [
        '-',
        '_',
        ' ',
    ];

    /**
     * Convert array indexes to json api spec indexes.
     *
     * @param array $array [description]
     *
     * @return array
     */
    public function transform($array)
    {
        $results = [];
        foreach ($array as $key => $value) {
            $results[$this->convert($key)] = is_array($value) ? $this->transform($value) : $value;
        }

        return $results;
    }

    /**
     * Do the actual conversion.
     *
     * @param
     *
     * @return
     */
    private function convert($key)
    {
        if (!is_string($key)) {
            return $key;
        }

        $firstLast = implode('', $this->forbiddenFirstOrLast);

        return str_replace($this->forbiddenCharacters, '', ltrim(rtrim($key, $firstLast), $firstLast));
    }
}
