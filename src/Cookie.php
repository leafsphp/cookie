<?php

namespace Leaf\Http;

/**
 * Leaf Cookie
 * ------------------------------------
 * Cookie management made simple with Leaf
 * 
 * @author Michael Darko
 * @since  2.0.0
 */
class Cookie
{
	/**
	 * Default options for cookie values
	 */
	protected static $options = [
		'expires' => 0,
		'path' => '',
		'domain' => '',
		'secure' => false,
		'httponly' => false,
		'samesite' => 'Lax',
	];

	/**
	 * Set default cookie options
	 * 
	 * @param array $defaults
	 * ```
	 * 'expires' => 0,
	 * 'path' => ',
	 * 'domain' => ',
	 * 'secure' => false,
	 * 'httponly' => false,
	 * 'samesite' => 'Lax',
	 * ```
	 */
	public static function setDefaults(array $defaults)
	{
		static::$options = array_merge(
			static::$options,
			$defaults
		);
	}

	/**
	 * Set a new cookie
	 *
	 * @param string|array $key Cookie name
	 * @param mixed $value Cookie value
	 * @param array $options Cookie settings
	 */
	public static function set($key, $value = '', array $options = [])
	{
		if (class_exists('Leaf\Eien\Server') && PHP_SAPI === 'cli') {
			\Leaf\Config::set('response.cookies', array_merge(
				\Leaf\Config::getStatic('response.cookies'),
				[$key => [$value, $options['expires'] ?? (time() + 604800)]],
			));

			return;
		}

		if (is_array($key)) {
			foreach ($key as $name => $value) {
				static::set($name, $value);
			}
		} else {
			setcookie($key, $value, [
				'expires' => ($options['expire'] ?? $options['expires']) ?? static::$options['expires'],
				'path' => $options['path'] ?? static::$options['path'],
				'domain' => $options['domain'] ?? static::$options['domain'],
				'secure' => $options['secure'] ?? static::$options['secure'],
				'httponly' => $options['httponly'] ?? static::$options['httponly'],
				'samesite' => $options['samesite'] ?? static::$options['samesite'],
			]);
		}
	}

	/**
	 * Shorthand method of setting a cookie + value + expire time
	 *
	 * @param string $name The name of the cookie
	 * @param string $value If string, the value of cookie; if array, properties for cookie including: value, expire, path, domain, secure, httponly
	 * @param string $expires When the cookie expires. Default: 7 days
	 */
	public static function simpleCookie(string $name, string $value, string $expires = null): void
	{
		self::set($name, $value, ['expires' => $expires ?? (time() + 604800)]);
	}

	/**
	 * Get all set cookies
	 */
	public static function all(): array
	{
		return $_COOKIE ?? [];
	}

	/**
	 * Get a particular cookie
	 * 
	 * @param string|array The cookie(s) to delete
	 */
	public static function get($param)
	{
		return $_COOKIE[$param] ?? null;
	}

	/**
	 * Unset a particular cookie
	 */
	public static function unset($key): void
	{
		if (is_array($key)) {
			foreach ($key as $name) {
				if (class_exists('Leaf\Eien\Server') && PHP_SAPI === 'cli') {
					\Leaf\Config::set('response.cookies', array_merge(
						\Leaf\Config::getStatic('response.cookies'),
						[$key => ['', time() - 604800]],
					));
				}

				self::unset($name);
			}
		} else {
			setcookie($key, '', time() - 86400);
		}
	}

	/**
	 * Delete a particular cookie
	 * 
	 * @param string|array The cookie(s) to delete
	 */
	public static function delete($key): void
	{
		static::unset($key);
	}

	/**
	 * Unset all cookies
	 */
	public static function unsetAll(): void
	{
		foreach ($_COOKIE as $key => &$value) {
			self::unset($key);
		}
	}

	/**
	 * Delete all cookies
	 */
	public static function deleteAll(): void
	{
		static::unsetAll();
	}
}
