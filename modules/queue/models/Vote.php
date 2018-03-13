<?php

namespace app\modules\queue\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "nmg_bestgood_vote".
 *
 * @property integer $id
 * @property integer $vote_user
 * @property string $vote_object
 * @property string $vote_time
 * @property string $vote_date
 * @property integer $creat_at
 */
class Vote extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nmg_bestgood_vote';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vote_user', 'creat_at'], 'integer'],
            [['vote_time', 'vote_date'], 'safe'],
            [['vote_object'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vote_user' => '投票人的id',
            'vote_object' => '投票的对象',
            'vote_time' => '投票时间',
            'vote_date' => '投票日期',
            'creat_at' => '数据创建时间',
        ];
    }

    /**
     * 获取当天的投票记录
     * @param $id
     * @return false|null|array
     */
    static function getTodayVote($id)
    {
         $data = self::find()->select('vote_object')
                    ->where(['vote_user' => (int)$id , 'vote_date' => date('Y-m-d')])
                    ->asArray()
                    ->all();
         if (!$data) return false;
         return ArrayHelper::getColumn($data,"vote_object");

    }

    /**
     * 记录投票关系
     * @param $vote_object
     * @param $vote_user
     * @return bool
     */
    static function addVoteRelation($vote_object,$vote_user)
    {
        $db = new self();
        $db->vote_user   = $vote_user;
        $db->vote_object = $vote_object;
        $db->vote_time   = date('Y-m-d H:i:s');
        $db->vote_date   = date('Y-m-d');
        $db->creat_at    = time();
        if ($db->save()){
            return true;
        }
        //var_dump($db->getErrors());
        return false;
    }

    public static function getDb()
    {
        return Yii::$app->get('db_hd');
    }
}
