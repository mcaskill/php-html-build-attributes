<?php

if (!function_exists('html_build_attributes')) {
	/**
	 * Generate a string of HTML attributes
	 *
	 * @param   array          $attr      Associative array of attribute names and values.
	 * @param   callable|null  $callback  Callback function to escape values for HTML attributes.
	 *                                    Defaults to `htmlspecialchars()`.
	 * @return  string  Returns a string of HTML attributes.
	 */
	function html_build_attributes(array $attr, callable $callback = null)
	{
		if (!count($attr)) {
			return '';
		}

		$html = array_map(
			function ($val, $key) use ($callback) {
				if (is_bool($val)) {
					return ($val ? $key : '');
				} elseif (isset($val)) {
					if ($val instanceof Closure) {
						$val = $val();
					} elseif ($val instanceof JsonSerializable) {
						$val = json_encode(
							$val->jsonSerialize(),
							(JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE)
						);
					} elseif (is_callable([ $val, 'toArray' ])) {
						$val = $val->toArray();
					} elseif (is_callable([ $val, '__toString' ])) {
						$val = strval($val);
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
						$val = call_user_func($callback, $val);
					} elseif (function_exists('esc_attr')) {
						$val = esc_attr($val);
					} else {
						$val = htmlspecialchars($val, ENT_QUOTES);
					}

					if (is_string($val)) {
						return sprintf('%1$s="%2$s"', $key, $val);
					}
				}
			},
			$attr,
			array_keys($attr)
		);

		return implode(' ', $html);
	}
}
