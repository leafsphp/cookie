<?php

use Leaf\Http\Cookie;

test('get returns a cookie value from $_COOKIE', function () {
    $_COOKIE['name'] = 'leaf';

    expect(Cookie::get('name'))->toBe('leaf');
});

test('get returns null for a missing cookie', function () {
    expect(Cookie::get('missing'))->toBeNull();
});

test('all returns every cookie in $_COOKIE', function () {
    $_COOKIE = ['a' => '1', 'b' => '2'];

    expect(Cookie::all())->toBe(['a' => '1', 'b' => '2']);
});

test('all returns an empty array when no cookies are set', function () {
    expect(Cookie::all())->toBe([]);
});
