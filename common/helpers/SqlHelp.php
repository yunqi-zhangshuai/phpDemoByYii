<?php


namespace app\common\helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SqlHelp
{
    /**
     * 开启记录sql开关
     * @param Model $model
     */
   public static function beginSql(Model $model)
   {
       $model->getConnection()->enableQueryLog();
   }


    /**
     * 得到刚刚执行的sql
     * @param Model $model
     * @return string
     */
   public static function getSql(Model $model)
   {
       $sqlArr = $model->getConnection()->getQueryLog()[0];
       return $sql = Str::replaceArray('?', $sqlArr['bindings'], $sqlArr['query']);
   }
}