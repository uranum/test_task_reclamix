<?php

if ( ! function_exists('config')) {
    function config($param) {
        $config = require 'config.php';
        if (key_exists($param, $config)) {

            return $config[$param];
        }
    }
}