<?php

namespace app\modules\queue\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "nmg_bestgood_hall".
 *
 * @property integer $id
 * @property string $hall_id
 * @property string $hall_area
 * @property string $hall_name
 * @property string $video_url
 * @property string $photo_url
 * @property integer $num
 */
class Hall extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nmg_bestgood_hall';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['num'], 'integer'],
            [['hall_id'], 'string', 'max' => 14],
            [['hall_area', 'hall_name'], 'string', 'max' => 80],
            [['video_url'], 'string', 'max' => 180],
            [['photo_url'], 'string', 'max' => 280],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Pk',
            'hall_id' => '营业厅编号',
            'hall_area' => '营业厅所在区域',
            'hall_name' => '营业厅名字',
            'video_url' => '视频地址',
            'photo_url' => '图集地址',
            'num' => '得票总数',
        ];
    }

    /**
     * 首页图集
     * @return array|\yii\db\ActiveRecord[]
     */
    static function getPhotos()
    {
        return self::find()->select('hall_id,photo_url,num')
            ->orderBy(['num' => SORT_DESC])
            ->asArray()
            ->all();
    }

    /**
     * 返回根据地区的排列的营业厅列表
     * @return array
     */
    static function getPhotosByAddress()
    {
        /*photo_url,hall_area,hall_id*/
        $data = self::find()->select('photo_url,hall_area,hall_id,hall_name')
            ->orderBy('id ASC')
            ->asArray()
            ->all();
        //根据地区分组数据
        $data = ArrayHelper::index($data, null, 'hall_area');

        foreach ($data as $k => &$v) {
            //合并一个地区下的营业厅
            $v['hall'] = array_values($data[$k]);
            //归并地址
            $v['address'] = $k;
            //删除没用的键值对
            foreach ($v as $m => $n) {
                if (!in_array($m, ['hall', 'address']) || $m == '0') {
                    unset($data[$k][$m]);
                }
            }
        }
        //重建索引
        $data = array_values($data);
        $key = 'queue_getPhotosByAddress2017120600462300';
        Yii::$app->cache->set($key,json_encode($data),3600 * 2);
        return  $data;

    }

    static function getOneInfo($id)
    {

        $data = self::find()->select('photo_url,hall_area,hall_id,hall_name,video_url,num,video_photo')
            ->where(['hall_id' => $id])
            ->asArray()
            ->one();
        if (!$data) return false;
        //var_dump($data);die;
        ///var_dump($data);die;
        if (in_array($data['hall_id'],['110','112'])){
            $photo = [];
            foreach (explode(',',$data['video_url']) as $m){
                $photo[] = "//image.buslive.cn/zm_nmg/img/{$m}.jpg";
            }
            $data['video_url'] = $photo;
            $data['is_photo']  = 2;
        }else{
            $data['is_photo']  = 1;

        }
        $data['full_name'] = $data['hall_area'] . '—' . $data['hall_name'];
        return $data;
    }

    public static function getDb()
    {
        return Yii::$app->get('db_hd');
    }


}
