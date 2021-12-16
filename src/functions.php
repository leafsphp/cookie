<?php

if (!function_exists('cookie')) {
    /**
     * Return cookie data/object or set cookie data
     *
     * @param mixed $key — The data to set
     * @param null $value
     * @return \Leaf\Http\Cookie|mixed|void|null
     */
    function cookie($key = null, $value = null)
    {
        if (!$key && !$value) {
            return new \Leaf\Http\Cookie();
        }

        if (!$value && is_string($key)) {
            return \Leaf\Http\Cookie::get($key);
        }

        \Leaf\Http\Cookie::set($key, $value);
    }
}
