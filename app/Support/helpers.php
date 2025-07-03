<?php

declare(strict_types=1);

use Illuminate\Support\Str;

if (! function_exists('normalize_name')) {
    /**
     * Normalize a name by replacing spaces and dashes with underscores and converting to lowercase.
     *
     * @param  string  $name  The name to normalize.
     * @return string The normalized name.
     */
    function normalize_name($name, $seperator = '_'): string
    {
        return Str::slug($name, $seperator);
    }
}

if (! function_exists('parse_variable')) {
    /**
     * Parse a variable to a specific type.
     *
     * @param  mixed  $variable  The variable to parse.
     * @param  string  $parse_as  The type to parse the variable as. Can be 'str', 'int', or 'bool'.
     * @return mixed The parsed variable.
     */
    function parse_variable($variable, $parse_as = 'bool')
    {
        switch ($parse_as) {
            case 'str':
            case 'string':
                return filter_var($variable, FILTER_SANITIZE_STRING);
            case 'int':
                return filter_var($variable, FILTER_VALIDATE_INT);
            case 'bool':
            case 'boolean':
                return filter_var($variable, FILTER_VALIDATE_BOOLEAN);
            default:
                return $variable;
        }
    }
}
