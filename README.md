# html_build_attributes

(PHP 5 >= 5.4)  
`html_build_attributes` — Generate a string of HTML attributes.

## Description

```php
string html_build_attributes( array $attr [, callable $callback = null ] )
```

Generate a string of HTML attributes from the associative array provided.

## Parameters

- `attr` — Associative array, or object containing properties, representing attribute names and values.  
  If `attr` is an object, then only public properties will be incorporated into the result.  
  If a value is an array, its values will be concatenated and delimited with a space.
- `$callback` — Callback function for escaping the values for the HTML attributes.

## Return Values

Returns a string of HTML attributes.

## Installation

### With Composer

```
$ composer require mcaskill/php-html-build-attributes
```

```json
{
	"repositories": [
		{
		  "type": "git",
		  "url": "https://gist.github.com/***.git"
		}
	],
	"require": {
		"mcaskill/php-html-build-attributes": "dev-master"
	}
}
```

### Without Composer

Why are you not using [composer](http://getcomposer.org/)? Download `Function.HTML-Build-Attributes.php` from the gist and save the file into your project path somewhere.