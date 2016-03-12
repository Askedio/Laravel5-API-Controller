<?php

namespace Askedio\Laravel5ApiController\Helpers;

class Api
{
    /** @var string */
    private static $version;

    /**
     * Get API version.
     *
     * @return string
     */
    public static function getVersion()
    {
        return self::$version ?: config('jsonapi.version');
    }

    /**
     * Set version.
     *
     * @param string $version
     *
     * @return void
     */
    public static function setVersion($version)
    {
        self::$version = $version;
    }

    /**
     * List of included options from input.
     *
     * @return Illuminate\Http\Request
     */
    public static function includes()
    {
        return request()->input('include') ? explode(',', request()->input('include')) : [];
    }

    /**
     * List of fields from input.
     *
     * @return array
     */
    public static function fields()
    {
        $_results = [];
        foreach (array_filter(request()->input('fields', [])) as $type => $members) {
            foreach (explode(',', $members) as $member) {
                $_results[$type][] = $member;
            }
        }

        return $_results;
    }
}
