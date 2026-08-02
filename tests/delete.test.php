<?php

use Leaf\Http\Cookie;

test('unset with a string key does not throw', function () {
    $_COOKIE['name'] = 'leaf';

    quietly(fn () => Cookie::unset('name'));

    expect(true)->toBeTrue();
});

test('unset with an array of keys does not fatal (regression: illegal offset)', function () {
    $_COOKIE = ['a' => '1', 'b' => '2'];

    // previously the whole array could be used as an array key in the
    // Leaf\Eien branch, fatalling with an illegal-offset TypeError
    quietly(fn () => Cookie::unset(['a', 'b']));

    expect(true)->toBeTrue();
});

test('unset of a cookie that does not exist does not throw', function () {
    quietly(fn () => Cookie::unset('ghost'));

    expect(true)->toBeTrue();
});

test('delete aliases unset for a string key', function () {
    $_COOKIE['name'] = 'leaf';

    quietly(fn () => Cookie::delete('name'));

    expect(true)->toBeTrue();
});

test('delete aliases unset for an array of keys', function () {
    $_COOKIE = ['a' => '1', 'b' => '2'];

    quietly(fn () => Cookie::delete(['a', 'b']));

    expect(true)->toBeTrue();
});

test('unsetAll iterates all cookies without throwing', function () {
    $_COOKIE = ['a' => '1', 'b' => '2', 'c' => '3'];

    quietly(fn () => Cookie::unsetAll());

    expect(true)->toBeTrue();
});

test('deleteAll aliases unsetAll', function () {
    $_COOKIE = ['a' => '1'];

    quietly(fn () => Cookie::deleteAll());

    expect(true)->toBeTrue();
});

test('unsetAll with no cookies set does not throw', function () {
    quietly(fn () => Cookie::unsetAll());

    expect(true)->toBeTrue();
});
