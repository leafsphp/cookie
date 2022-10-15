<?php

if (!function_exists('cookie')) {
    /**
     * Return cookie data/object or set cookie data
     *
     * @return \Leaf\Http\Cookie
     */
    function cookie()
    {
        $cookie = Leaf\Config::get('cookie')['instance'] ?? null;

        if (!$cookie) {
            $cookie = new \Leaf\Http\Cookie();
            Leaf\Config::set('cookie', ['instance' => $cookie]);
        }

        return $cookie;
    }
}
