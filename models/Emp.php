<?php
/**
 * Created by PhpStorm.
 * User: 云起
 * Date: 2017-07-28
 * Time: 11:07
 * File: Emp.php
 */

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class Emp
 * @package app\models
 * 测试数据curd
 */
class Emp extends ActiveRecord

{
    /**
     * 返回当前表名
     * @return string
     */
    public static function tableName()
    {
        return 'emp';
    }

    /**
     * @return array
     */
    public function actionFind()
    {
        return (new \yii\db\Query())
            ->select(['dept.dname as name', 'job', 'ename'])
            ->from('emp')
            ->leftJoin('dept', 'dept.deptno=emp.deptno')
            ->all();


    }
}

