<?php
/**
 * Created by PhpStorm.
 * User: zhangshuai
 * Date: 18-6-10
 * Time: 下午7:17
 */

namespace app\common\Designpatterns\factory;



abstract class Text
{
  private $text;

  public function __construct(string $text)
  {
      $this->text = $text;
  }
}