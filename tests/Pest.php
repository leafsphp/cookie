<?php

/**
 * Read the protected static Cookie::$options via reflection.
 */
function cookieOptions(): array
{
    $prop = new ReflectionProperty(\Leaf\Http\Cookie::class, 'options');
    $prop->setAccessible(true);

    return $prop->getValue();
}

/**
 * Overwrite the protected static Cookie::$options via reflection.
 */
function setCookieOptions(array $options): void
{
    $prop = new ReflectionProperty(\Leaf\Http\Cookie::class, 'options');
    $prop->setAccessible(true);
    $prop->setValue(null, $options);
}

/**
 * The pristine default options as declared in Cookie.php.
 */
function defaultCookieOptions(): array
{
    return [
        'expires' => 0,
        'path' => '',
        'domain' => '',
        'secure' => false,
        'httponly' => false,
        'samesite' => 'Lax',
    ];
}

/**
 * Run a callable with warnings suppressed (setcookie warns in CLI because
 * headers are already sent). Re-throws anything that is a real Error.
 */
function quietly(callable $fn): void
{
    set_error_handler(function () {
        return true; // swallow warnings/notices only
    });

    try {
        $fn();
    } finally {
        restore_error_handler();
    }
}

uses()
    ->beforeEach(function () {
        $_COOKIE = [];
        setCookieOptions(defaultCookieOptions());
    })
    ->in(__DIR__);
