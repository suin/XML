<?php

set_include_path(get_include_path().PATH_SEPARATOR.dirname(__DIR__).'/Source');

spl_autoload_register(function($class) {
	require_once str_replace(array('\\', '_'), '/', $class) . '.php';
});
