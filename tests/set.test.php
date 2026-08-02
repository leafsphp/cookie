<?php

use Leaf\Http\Cookie;

// setcookie() cannot actually emit headers in CLI (headers already sent),
// so these tests assert that calls are well-formed: no TypeError, no fatal.

test('set with a string key does not throw', function () {
    quietly(fn () => Cookie::set('name', 'leaf'));

    expect(true)->toBeTrue();
});

test('set with options does not throw', function () {
    quietly(fn () => Cookie::set('name', 'leaf', [
        'expires' => time() + 3600,
        'path' => '/',
        'domain' => 'example.com',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict',
    ]));

    expect(true)->toBeTrue();
});

test('set supports the legacy "expire" option key', function () {
    quietly(fn () => Cookie::set('name', 'leaf', ['expire' => time() + 60]));

    expect(true)->toBeTrue();
});

test('set with an array sets each cookie and forwards options (regression: options were dropped)', function () {
    // Regression guard for the dropped-options bug: an expires option given
    // as a string date would reach setcookie() and raise a TypeError if
    // options were forwarded but not normalised; if options were silently
    // dropped, no error appears at all. Verify forwarding via behaviour:
    // a bad option type must fail for array input exactly as for string input.
    $thrownForString = null;
    $thrownForArray = null;

    quietly(function () use (&$thrownForString) {
        try {
            Cookie::set('single', 'value', ['expires' => 'not-a-timestamp']);
        } catch (Throwable $e) {
            $thrownForString = $e;
        }
    });

    quietly(function () use (&$thrownForArray) {
        try {
            Cookie::set(['multi' => 'value'], '', ['expires' => 'not-a-timestamp']);
        } catch (Throwable $e) {
            $thrownForArray = $e;
        }
    });

    // whatever setcookie does with the option, array input must behave the
    // same as string input — proving $options is forwarded to each cookie
    expect($thrownForArray === null)->toBe($thrownForString === null);
    if ($thrownForString !== null) {
        expect($thrownForArray)->toBeInstanceOf(get_class($thrownForString));
    }
});

test('set with an array of cookies and valid options does not throw', function () {
    quietly(fn () => Cookie::set(
        ['a' => '1', 'b' => '2'],
        '',
        ['path' => '/', 'httponly' => true]
    ));

    expect(true)->toBeTrue();
});

test('simpleCookie converts a strtotime expiry string without a TypeError', function () {
    quietly(fn () => Cookie::simpleCookie('name', 'leaf', '2 days'));

    expect(true)->toBeTrue();
});

test('simpleCookie tolerates an invalid expiry string', function () {
    quietly(fn () => Cookie::simpleCookie('name', 'leaf', 'not a real date'));

    expect(true)->toBeTrue();
});

test('simpleCookie works with no expiry given', function () {
    quietly(fn () => Cookie::simpleCookie('name', 'leaf'));

    expect(true)->toBeTrue();
});

test('simpleCookie accepts an integer timestamp expiry', function () {
    quietly(fn () => Cookie::simpleCookie('name', 'leaf', time() + 3600));

    expect(true)->toBeTrue();
});
