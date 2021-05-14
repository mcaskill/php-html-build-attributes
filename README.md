# html_build_attributes

> PHP 5 >= 5.4, PHP 7, PHP 8

Generate a string of HTML attributes.

## Installation

Using [Composer](https://getcomposer.org/):

```
$ composer require mcaskill/php-html-build-attributes
```

Alternatively, download `Function.HTML-Build-Attributes.php` from the package
source and save the file into your project path somewhere.

## Upgrading

This package follows [semantic versioning](https://semver.org/), which means
breaking changes may occur between major releases.

## API

```php
html_build_attributes( array|object $attr [, callable $callback = null ] ) : string
```

### Parameters

- `attr` — Associative array or object containing properties, representing
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
  
- `callback` — Callback function to escape the values for HTML attributes.
  
  If no function is provided, [`htmlspecialchars()`][function.htmlspecialchars]
  is used;
  
  If using WordPress, the [`esc_attr()`][wp.esc_attr] function is used.

### Return Values

Returns a string of HTML attributes or a empty string if `attr` is invalid or empty.

## Examples

### Example #1: Simple usage of html_build_attributes()

```php
$attr = [
  'type'           => 'file',
  'id'             => 'avatar',
  'name'           => 'avatar',
  'class'          => [ 'form-control', 'form-control-sm' ],
  'multiple'       => true,
  'disabled'       => false,
  'accept'         => implode(',', [ 'image/png', 'image/jpeg' ]),
  'data-max-files' => 3,
];

echo '<input ' . html_build_attributes($attr) . '>';
```

The above example will output:

```html
<input type="file" id="avatar" name="avatar" class="form-control form-control-sm" multiple accept="image/png,image/jpeg" data-max-files="3">
```

[class.closure]:             https://php.net/class.closure
[function.htmlspecialchars]: https://php.net/function.htmlspecialchars
[function.json_encode]:      https://php.net/function.json_encode
[object.invoke]:             https://php.net/oop5.magic#object.invoke
[wp.esc_attr]:               https://developer.wordpress.org/reference/functions/esc_attr/
