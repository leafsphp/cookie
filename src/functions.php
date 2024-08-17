<?php

if (!function_exists('cookie')) {
    /**
     * Return cookie data/object or set cookie data
     *
     * @return \Leaf\Http\Cookie
     */
    function cookie()
    {
        if (!(\Leaf\Config::getStatic('cookie'))) {
            \Leaf\Config::singleton('cookie', function () {
                return new \Leaf\Http\Cookie();
            });
        }

        return \Leaf\Config::get('cookie');
    }
}
