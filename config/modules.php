<?php

$configs = [];
$modules = [];
foreach(glob(__DIR__ . '/../modules/*/config.php') as $config_file){
    $config_one = require $config_file;
    $key = key($config_one);
    $modules[] = $key;
    $configs[$key] = $config_one[$key];
}
/*$configs['doconline'] = [
    'class' => 'Kaopur\yii2_doc_online\Module',
    'defaultRoute' => 'index', //默认控制器
    'appControllers' => false, //是否检测app\controllers命名空间下的控制器
    'suffix' => 'api', //api后缀
    'prefix' => '', //api前缀
    'modules' => $modules  //需要生成文档的模块
];*/
return $configs;