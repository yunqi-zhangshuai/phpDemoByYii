<?php
/**
 * Created by PhpStorm.
 * User: zhangshuai
 * Date: 18-7-26
 * Time: 下午12:16
 */

$database = [
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'database' => 'yii2hd',
    'username' => 'root',
    'password' => '55743011',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    //'prefix' => DB_TABLEPREFIX,
];

use Illuminate\Database\Capsule\Manager as Capsule;


$capsule = new Capsule;

// 创建链接
$capsule->addConnection($database);

// 设置全局静态可访问DB
$capsule->setAsGlobal();

// 启动Eloquent
$capsule->bootEloquent();








