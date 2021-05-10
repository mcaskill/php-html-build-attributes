# html_build_attributes

(PHP 5 >= 5.4, PHP 7, PHP 8)  
`html_build_attributes` — Generate a string of HTML attributes.

## Description

```php
string html_build_attributes( array $attr [, callable $callback = null ] )
```

Generate a string of HTML attributes from the associative array provided.

## Parameters

- `attr` — Associative array, or object containing properties, representing
  attribute names and values.
  
  If `attr` is a non-iterable object, then only accessible non-static properties
  will be incorporated into the result.
  
  If an attribute name is an empty string, the attribute is ignored.
  The attribute name will be trimmed of leading/trailing whitespace.
  
  If an attribute value is callable (either as a [`Closure`][class.closure] or
  invokable), it is called and the returned value continues processing.
  
  If an attribute value is `null`, the attribute is ignored.
  
  If an attribute value is an arrayable or a stringable object, it is converted
  to its primitive type.
  
  If an attribute value is a boolean and `true`, the attribute will be rendered
  without a value, otherwise the attribute is ignored.
  
  If an attribute value is an array, only numbers and strings are accepted.
  Strings of the array will be trimmed of leading/trailing whitespace.
  If the filtered array is empty, the attribute is ignored.
  
  Any other value will be serialized using [`json_encode()`][function.json_encode].
  
- `callback` — Callback function for escaping the values for the HTML attributes.
  
  If no sanitizer is provided, [`htmlspecialchars()`][function.htmlspecialchars]
  is used;
  
  If using WordPress, the [`esc_attr()`][wp.esc_attr] function is used.

## Return Values

Returns a string of HTML attributes or a empty string if `attr` is invalid or empty.

## Installation

### With Composer

```
$ composer require mcaskill/php-html-build-attributes
```

### Without Composer

Why are you not using [composer](http://getcomposer.org/)?
Download `Function.HTML-Build-Attributes.php` from the gist and save the file
into your project path somewhere.

[class.closure]:             https://php.net/class.closure
[function.htmlspecialchars]: https://php.net/function.htmlspecialchars
[function.json_encode]:      https://php.net/function.json_encode
[object.invoke]:             https://php.net/oop5.magic#object.invoke
[wp.esc_attr]:               https://developer.wordpress.org/reference/functions/esc_attr/
