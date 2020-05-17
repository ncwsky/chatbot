<?php


namespace Commune\Support\Utils;


class ArrayUtils
{

    /**
     * @param mixed $data
     * @return mixed|array
     */
    public static function recursiveToArray($data)
    {
        if (is_object($data) && method_exists($data, 'toArray')) {
            return $data->toArray();
        }

        if (is_iterable($data)) {
            $results = [];
            foreach ($data as $key => $value) {
                $results[$key] = static::recursiveToArray($value);
            }
            return $results;

        }

        return $data;
    }


    public static function fieldsAreRequired(array $fields, array $data) : ? string
    {
        foreach ($fields as $field) {
            $val = $data[$field] ?? null;
            if (is_null($val) || $val === '') {
                return $field;
            }
        }

        return null;
    }
}