<?php

$configs = [];
$modules = [];
foreach(glob(__DIR__ . '/../modules/*/config.php') as $config_file){
    $config_one = require $config_file;
    $key = key($config_one);
    $modules[] = $key;
    $configs[$key] = $config_one[$key];
}

return $configs;