<?php
/**
 * Created by PhpStorm.
 * User: zhangshuai
 * Date: 18-6-10
 * Time: 下午7:13
 */

namespace app\common\Designpatterns\factory;


abstract class AbstractFactory
{

    abstract public function createText(string $string): Text;

}