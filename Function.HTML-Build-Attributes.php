<?php

if (!function_exists('html_build_attributes')) {
    /**
     * Generate a string of HTML attributes.
     *
     * @param  array|object  $attr     Associative array or object containing properties,
     *     representing attribute names and values.
     * @param  callable|null $callback Callback function to escape the values for HTML attributes.
     *     Defaults to `esc_attr()`, if available, otherwise `htmlspecialchars()`.
     * @return string Returns a string of HTML attributes
     *     or a empty string if $attr is invalid or empty.
     */
    function html_build_attributes($attr, callable $callback = null)
    {
        if (is_object($attr) && !($attr instanceof \Traversable)) {
            $attr = get_object_vars($attr);
        }

        if (!is_array($attr) || !count($attr)) {
            return '';
        }

        $html = [];
        foreach ($attr as $key => $val) {
            if (is_string($key)) {
                $key = trim($key);

                if (strlen($key) === 0) {
                    continue;
                }
            }

            if (is_object($val) && is_callable($val)) {
                $val = $val();
            }

            if (is_null($val)) {
                continue;
            }

            if (is_object($val)) {
                if (is_callable([ $val, 'toArray' ])) {
                    $val = $val->toArray();
                } elseif (is_callable([ $val, '__toString' ])) {
                    $val = strval($val);
                }
            }

            if (is_bool($val)) {
                if ($val) {
                    $html[] = $key;
                }
                continue;
            } elseif (is_array($val)) {
                $val = implode(' ', array_reduce($val, function ($tokens, $token) {
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

                if (strlen($val) === 0) {
                    continue;
                }
            } elseif (!is_string($val) && !is_numeric($val)) {
                $val = json_encode($val, (JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));
            }

            if (is_callable($callback)) {
                $val = $callback($val);
            } elseif (function_exists('esc_attr')) {
                $val = esc_attr($val);
            } else {
                $val = htmlspecialchars($val, ENT_QUOTES);
            }

            $html[] = sprintf('%1$s="%2$s"', $key, $val);
        }

        return implode(' ', $html);
    }
}
