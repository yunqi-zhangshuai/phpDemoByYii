<?php

namespace app\modules\queue\models;

use Yii;

/**
 * This is the model class for table "nmg_bestgood_user".
 *
 * @property integer $id
 * @property string $openid
 * @property integer $num
 * @property string $save_time
 * @property integer $creat_at
 * @property integer $login_time
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nmg_bestgood_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['num', 'creat_at', 'login_time'], 'integer'],
            [['save_time'], 'safe'],
            [['openid'], 'string', 'max' => 38],
            ['num', 'compare', 'compareValue' => 0, 'operator' => '>='],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Pk',
            'openid' => '微信openid',
            'num' => '当天可投票次数,默认是5',
            'save_time' => '保存时间',
            'creat_at' => '数据创建时间',
            'login_time' => '登录时间',
        ];
    }

    /**
     * 获取当前用户信息
     * @param $openid
     * @return self|false
     */
    static function getUser($openid)
    {
        $data = self::find()
                    ->select('id,openid,num,login_time')
                    ->where(['openid' => $openid])
                    ->one();
        if(!$data) return false;
        return $data;
    }

    /**
     * 添加用户
     * @param array $info
     * @return User|array|bool
     */
    static function addUser(array $info)
    {
        $user = new self();
        $user->openid     = $info['username'];
        $user->num        = 5;
        $user->save_time  = date('Y-m-d H:i:s');
        $user->creat_at   = time();
        $user->login_time = strtotime(date('Y-m-d').'23:59:59');

        if ($user->save()){
            return $user;
        }
        return false;
    }

    /**
     * 我的剩余投票机会
     * @param $openid
     * @return false|null|string
     */
    static function getMyNum($openid)
    {
        return self::find()->select('num')
                           ->where(['openid' => $openid])
                           ->scalar();
    }

    /**
     * @return null|object|\yii\db\Connection
     * @throws \yii\base\InvalidConfigException
     */
    public static function getDb()
    {
        return Yii::$app->get('db_hd');
    }
}
