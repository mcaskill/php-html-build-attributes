<?php

$inputBuildsExpectedEmptyString = function ($input) : void {
    $output = html_build_attributes($input);

    expect($output)->toBeString()->toBeEmpty();
};

/**
 * @param mixed $input The variable to be passed to the function.
 */
it('returns an empty string', $inputBuildsExpectedEmptyString)->with([
    'scalar',
    (new stdClass()),
    (new ArrayIterator()),
    [
        [
            ''        => 'empty',
            '   '     => 'whitespace',
            'null'    => null,
            'false'   => false,
            'array'   => [],
        ],
    ],
]);

$inputBuildsExpectedHtml = function ($html, $input) : void {
    $output = html_build_attributes($input);

    expect($output)->toBe($html);
};

/**
 * @param string $html  The expected return value.
 * @param mixed  $input The variable to be passed to the function.
 */
it('generates HTML attributes', $inputBuildsExpectedHtml)->with([
    [
        'id="foobar" required class="foo baz 0 qux" data-fn="rand"',
        [
            'id'       => function () {
                return 'foobar';
            },
            'required' => true,
            'class'    => [ 'foo', null, '', '   ', '  baz ', 0, true, false, 'qux' ],
            'data-fn'  => 'rand',
        ],
    ],
    [
        'data-arr="one two three"',
        [
            'data-arr' => new class {
                public function toArray() : array
                {
                    return [ 'one', 'two', 'three' ];
                }
            },
        ],
    ],
    [
        'data-str="bazqux"',
        [
            'data-str' => new class {
                public function __toString() : string
                {
                    return 'bazqux';
                }
            },
        ],
    ],
    [
        'data-obj="{&quot;a&quot;:1,&quot;b&quot;:2,&quot;c&quot;:3}"',
        [
            'data-obj' => new class {
                public $a = 1;
                public $b = 2;
                public $c = 3;
            },
        ],
    ],
]);

$inputBuildsExpectedEscape = function ($html, $args) : void {
    $output = html_build_attributes(...$args);

    expect($output)->toBe($html);
};

/**
 * @param string   $html   The expected return value.
 * @param mixed    $input  The variable to be passed to the function.
 * @param callable $escape The custom escape callback to be passed to the function.
 */
it('escapes HTML attributes', $inputBuildsExpectedEscape)->with([
    [
        'id="FOOBAR"',
        [
            [
                'id' => 'foobar',
            ],
            function ($value, $name) {
                expect($name)->toBe('id');

                return strtoupper($value);
            },
        ],
    ],
]);
