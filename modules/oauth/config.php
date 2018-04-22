<?php
/**
 * 加入此模块的配置文件,系统会自动引入
 * 详细配置项见yii\base\Module.php
 */
$model_name = 'oauth';
return [
    $model_name => [
        'class' => "app\\modules\\{$model_name}\\Module",
        'defaultRoute' => 'index',   //默认控制器
    ],
];