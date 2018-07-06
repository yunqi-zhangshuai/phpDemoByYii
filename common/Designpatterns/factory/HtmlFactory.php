<?php
/**
 * Created by PhpStorm.
 * User: zhangshuai
 * Date: 18-6-10
 * Time: 下午7:25
 */

namespace app\common\Designpatterns\factory;


class HtmlFactory extends AbstractFactory
{
    public function createText(string $string): Text
    {
        return new HtmlText($string);
    }

}