<?php

if (!function_exists('html_build_attributes')) {
    /**
     * Generate a string of HTML attributes.
     *
     * @param  array|object  $attributes
     *     Associative array or object containing properties,
     *     representing attribute names and values.
     * @param  callable|null $escape
     *     Callback function to escape the values for HTML attributes.
     *     Accepts two parameters: 1. attribute value, 2. attribute name.
     *     Defaults to `esc_attr()`, if available, otherwise `htmlspecialchars()`.
     * @return string Returns a string of HTML attributes
     *     or a empty string if $attributes is invalid or empty.
     */
    function html_build_attributes($attributes, callable $escape = null)
    {
        if (is_object($attributes) && !($attributes instanceof \Traversable)) {
            $attributes = get_object_vars($attributes);
        }

        if (!is_array($attributes) || !count($attributes)) {
            return '';
        }

        if (is_null($escape)) {
            if (function_exists('esc_attr')) {
                $escape = function ($value) {
                    return esc_attr($value);
                };
            } else {
                $escape = function ($value) {
                    return htmlspecialchars($value, ENT_QUOTES, null, false);
                };
            }
        }

        $html = [];
        foreach ($attributes as $attribute_name => $attribute_value) {
            if (is_string($attribute_name)) {
                $attribute_name = trim($attribute_name);

                if (strlen($attribute_name) === 0) {
                    continue;
                }
            }

            if (is_object($attribute_value) && is_callable($attribute_value)) {
                $attribute_value = $attribute_value();
            }

            if (is_null($attribute_value)) {
                continue;
            }

            if (is_object($attribute_value)) {
                if (is_callable([ $attribute_value, 'toArray' ])) {
                    $attribute_value = $attribute_value->toArray();
                } elseif (is_callable([ $attribute_value, '__toString' ])) {
                    $attribute_value = strval($attribute_value);
                }
            }

            if (is_bool($attribute_value)) {
                if ($attribute_value) {
                    $html[] = $attribute_name;
                }
                continue;
            } elseif (is_array($attribute_value)) {
                $attribute_value = implode(' ', array_reduce($attribute_value, function ($tokens, $token) {
                    if (is_string($token)) {
                        $token = trim($token);

                        if (strlen($token) > 0) {
                            $tokens[] = $token;
                        }
                    } elseif (is_numeric($token)) {
                        $tokens[] = $token;
                    }

                    return $tokens;
                }, []));

                if (strlen($attribute_value) === 0) {
                    continue;
                }
            } elseif (!is_string($attribute_value) && !is_numeric($attribute_value)) {
                $attribute_value = json_encode($attribute_value, (JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));
            }

            $html[] = sprintf(
                '%1$s="%2$s"',
                $attribute_name,
                $escape($attribute_value, $attribute_name)
            );
        }

        return implode(' ', $html);
    }
}
