<?php

if (!function_exists('cookie')) {
    /**
     * Return cookie data/object or set cookie data
     *
     * @param string|null $key — The cookie data to set/get
     * @param mixed $key — The data to set
     */
    function cookie($key = null, $value = null)
    {
        if (!$key && !$value) {
            return new \Leaf\Http\Cookie();
        }

        if (!$value && is_string($key)) {
            return \Leaf\Http\Cookie::get($key);
        }

        return \Leaf\Http\Cookie::set($key, $value);
    }
}
