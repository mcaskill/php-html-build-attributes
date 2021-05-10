<?php

if (!function_exists('html_build_attributes')) {
    /**
     * Generate a string of HTML attributes
     *
     * @param  array|object  $attr     Associative array or object of attribute names and values.
     * @param  callable|null $callback Callback function to escape values for HTML attributes.
     *                                 Defaults to `htmlspecialchars()`.
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
            if (is_null($val)) {
                continue;
            }

            if (is_string($key)) {
                $key = trim($key);

                if (strlen($key) === 0) {
                    continue;
                }
            }

            if (is_object($val)) {
                if ($val instanceof Closure) {
                    $val = $val();
                } elseif ($val instanceof JsonSerializable) {
                    $val = json_encode($val, (JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));
                } elseif (is_callable([ $val, 'toArray' ])) {
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
            }

            if (is_array($val)) {
                if (function_exists('is_blank')) {
                    $filter = function ($var) {
                        return !is_blank($var);
                    };
                } else {
                    $filter = function ($var) {
                        return !empty($var) || is_numeric($var);
                    };
                }
                $val = implode(' ', array_filter($val, $filter));
            }

            if (is_callable($callback)) {
                $val = $callback($val);
            } elseif (function_exists('esc_attr')) {
                $val = esc_attr($val);
            } else {
                $val = htmlspecialchars($val, ENT_QUOTES);
            }

            if (is_string($val) || is_numeric($val)) {
                $html[] = sprintf('%1$s="%2$s"', $key, $val);
                continue;
            }
        }

        return implode(' ', $html);
    }
}
