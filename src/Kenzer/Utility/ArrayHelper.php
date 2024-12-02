<?php

declare(strict_types=1);

namespace Kenzer\Utility;

class ArrayHelper
{
    public static function map(array $array, callable $callback)
    {
        $keys = array_keys($array);

        try {
            $items = array_map($callback, $array, $keys);
        } catch (ArgumentCountError) {
            $items = array_map($callback, $array);
        }

        return array_combine($keys, $items);
    }

    public static function where($array, callable $callback) : array
    {
        return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Filter items where the value is not null.
     *
     * @param  array  $array
     * @return array
     */
    public static function whereNotNull($array) : array
    {
        return static::where($array, fn ($value) => ! is_null($value));
    }
}
