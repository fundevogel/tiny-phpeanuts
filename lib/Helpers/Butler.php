<?php

namespace Fundevogel\Helpers;


/**
 * Class Butler
 *
 * This class contains useful helper functions, pretty much like a butler
 * Powered by https://getkirby.com
 *
 * @package tiny-phpeanuts
 */
class Butler
{
    /**
     * Better alternative for implode()
     *
     * @param  string  $value The value to join
     * @param  string  $separator The string to join by
     * @param  int     $length The min length of values.
     * @return array   An array of found values
     */
    public static function join($value, $separator = ', ')
    {
        if (is_string($value) === true) {
            return $value;
        }

        return implode($separator, $value);
    }


    /**
     * Plucks a single column from an array
     *
     * <code>
     * $array[] = [
     *   'id' => 1,
     *   'username' => 'homer',
     * ];
     *
     * $array[] = [
     *   'id' => 2,
     *   'username' => 'marge',
     * ];
     *
     * $array[] = [
     *   'id' => 3,
     *   'username' => 'lisa',
     * ];
     *
     * var_dump(A::pluck($array, 'username'));
     * // result: ['homer', 'marge', 'lisa'];
     * </code>
     *
     * @param array $array The source array
     * @param string $key The key name of the column to extract
     * @return array The result array with all values
     *               from that column.
     */
    public static function pluck(array $array, string $key)
    {
        $output = [];

        foreach ($array as $a) {
            if (isset($a[$key]) === true) {
                $output[] = $a[$key];
            }
        }

        return $output;
    }
}
