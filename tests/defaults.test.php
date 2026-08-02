<?php

use Leaf\Http\Cookie;

test('default options match the documented defaults', function () {
    expect(cookieOptions())->toBe(defaultCookieOptions());
});

test('setDefaults merges into the existing options', function () {
    Cookie::setDefaults(['path' => '/', 'secure' => true]);

    $options = cookieOptions();

    expect($options['path'])->toBe('/')
        ->and($options['secure'])->toBeTrue()
        // untouched keys keep their defaults
        ->and($options['domain'])->toBe('')
        ->and($options['httponly'])->toBeFalse()
        ->and($options['samesite'])->toBe('Lax')
        ->and($options['expires'])->toBe(0);
});

test('setDefaults lets unknown keys pass through', function () {
    Cookie::setDefaults(['partitioned' => true]);

    expect(cookieOptions())->toHaveKey('partitioned')
        ->and(cookieOptions()['partitioned'])->toBeTrue();
});

test('setDefaults with an empty array leaves options unchanged', function () {
    Cookie::setDefaults([]);

    expect(cookieOptions())->toBe(defaultCookieOptions());
});

test('later setDefaults calls stack on earlier ones', function () {
    Cookie::setDefaults(['path' => '/app']);
    Cookie::setDefaults(['secure' => true]);

    expect(cookieOptions()['path'])->toBe('/app')
        ->and(cookieOptions()['secure'])->toBeTrue();
});

test('set uses merged defaults without throwing', function () {
    Cookie::setDefaults(['path' => '/', 'httponly' => true]);

    quietly(fn () => Cookie::set('name', 'leaf'));

    expect(true)->toBeTrue();
});
