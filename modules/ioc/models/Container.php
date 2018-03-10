<?php
/**
 * Created by PhpStorm.
 * User: zhangshuai
 * Date: 18-2-25
 * Time: 下午9:10
 */

namespace app\modules\ioc\models;


class Container
{

    private $_definitions;

    public function set($class, $definition)
    {
        $this->_definitions[$class] = $definition;
    }

    /**
     * @param $class
     * @param $params
     * @return SendEmailer
     * @@throws \Exception
     */
    public function get($class, $params)
    {

        if (isset($this->_definitions[$class]) &&
            is_callable($this->_definitions[$class], true)) {
            $definition = $this->_definitions[$class];
            return call_user_func($definition, $this, $params);
        }
        throw new \Exception('error');
    }


}