<?php

namespace app\modules\queue\controllers;

use app\controllers\BaseController;
use app\modules\queue\models\Hall;
use app\modules\queue\models\User;
use app\modules\queue\models\Vote;
use yii\base\Exception;
use yii\helpers\Json;
/**
 * 投票demo
 * Class IndexController
 * @package app\modules\queue\controllers
 */
class IndexController extends BaseController
{
    public $layout = false;


    public function init()
    {
        $this->login_key = 'queue_vote';
        parent::init();
    }

    /**
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionIndex()
    {
        $get = \Yii::$app->request->get();
        //构建一个虚拟用户身份
        if(empty($get['username'])) {
          throw  new Exception('username不能为空!');
        }
        $user_arr = [
            'username' => $get['username'],
        ];
       //$user_arr['username'] = 'ajfkdjfkljdsfljdslfjds';
        //用户是否注册
        if (!($login = User::getUser($user_arr['username']))) {
            //注册用户信息
            if (!($login = User::addUser($user_arr))) {
                return $this->redirect('http://y.buslive.cn/50x.html');
            }
        }
        //投票次数重置
        if (!isset($new)) {
            if (time() > $login->login_time) {
                $login->num = 5;
                $login->login_time = strtotime(date('Y-m-d') . '23:59:59');
                if (!($login->save())) {
                    return $this->redirect('http://y.buslive.cn/50x.html');
                }
            }
        }
        $this->login($this->login_key,['id' => $login->id, 'openid' => $login->getAttribute('openid')]);
        return $this->render('index');

    }

    /**
     * 图片列表api
     * @desc 使用get方式 请使用  &quot;&lt;?php echo \yii\helpers\Url::to(['photosapi'])?&gt;&quot;
     * @return int state 操作码，0表示非法请求 , 1表示成功查询；2活动已经结束；3网络失败
     * @return string msg 提示信息
     * @exception 400 参数传递错误
     * @exception 500 服务器内部错误
     */
    public function actionPhotosapi()
    {
        $sign = \Yii::$app->getRequest()->get('sign');
        if (!$this->loginInfo($this->login_key)){
            return Json::encode(['msg' => '非法请求' , 'state' => 0]);
        }
        if (((int)$sign) == 1) {
            $key = 'queue_getPhotosByAddress2017120600462300';
            if( !($photos = \Yii::$app->cache->get($key))){
                $photos = Hall::getPhotosByAddress();
            }
        } else {
            $photos = Hall::getPhotos();
        }
        if (!$photos) {
            return Json::encode(['msg' => '网络失败', 'state' => 3]);
        }

        return Json::encode(['photos' => $photos, 'state' => 1]);


    }


    /**
     * 或许营业厅详情页
     * @desc 使用get方式 请使用  &quot;&lt;?php echo \yii\helpers\Url::to(['oneinfoapi'])?&gt;&quot;
     * @param string $id |营业厅编号|yes|其他说明|
     * @return int state 操作码，0表示非法请求 , 1表示正常返回信息 ; 2表示没有信息
     * @return string msg 提示信息
     * @exception 400 参数传递错误
     * @exception 500 服务器内部错误
     */
    public function actionOneinfoapi()
    {
        $id = \Yii::$app->getRequest()->get('id');
        if (!$this->loginInfo($this->login_key) || !is_string($id)) {
            return Json::encode(['msg' => '非法请求', 'state' => 0]);
        }
        /*if (!($info = Hall::getOneInfo($id))) {
            return Json::encode(['msg' => '没有信息!', 'state' => 2]);
        }*/
        \Yii::$app->session->set('video'.$this->login_key,['id' => $id]);
        return Json::encode(['msg' => '查询成功', 'state' => 1]);
    }


    public function actionInfo()
    {
        $video = \Yii::$app->session->get('video'.$this->login_key);
        if (!$this->loginInfo($this->login_key) || !$video || !($info = Hall::getOneInfo($video))) {
            return $this->redirect('http://y.buslive.cn/50x.html');
        }
        \Yii::$app->session->set('queue_info','aaaasb');
        return $this->render('vote',['video' => Json::encode($info)]);

    }

    /**
     * 投票接口
     * @desc 使用post方式 请使用  &quot;&lt;?php echo \yii\helpers\Url::to(['voteapi'])?&gt;&quot;
     * @param string $_csrf |POST访问验证|yes|<?php echo \Yii::$app->request->getCsrfToken()?>
     * @param string $id |当前投票视频的id|yes|
     * @return int state 操作码，0表示非法请求; 1表示投票成功; 2表示投票失败;3当天投票次数已经够了;4不能对一个营业厅重复投票 ；5活动已经结束
     * @return string msg 提示信息
     * @exception 400 参数传递错误
     * @exception 500 服务器内部错误
     */

    public function actionVoteapi()
    {

        $request = \Yii::$app->getRequest();
        $id = $request->Post('id');
        if (!($login_arr = $this->loginInfo($this->login_key)) || !is_string($id)
              || !$request->isAjax || !$request->isPost ) {
            return Json::encode(['msg' => '非法请求', 'state' => 0]);
        }
        //判断当前是否有此营业厅
        if (!($hall = Hall::findOne(['hall_id'=>$id]))){
            return Json::encode(['msg' => '非法请求', 'state' => 0]);
        }
        //得到当前用户信息
        $user = $user_obj = User::getUser($login_arr['openid']);
        //是否重置初始投票次数
        if (time() > $user->login_time) {
            $is_new = true;
            $user->num = 5;
            $user->login_time = strtotime(date('Y-m-d') . '23:59:59');
            if (!($user->save())) {
                return Json::encode(['msg' => '投票失败', 'state' => 2]);
            }
        }

        //判断当前用户投票次数
        if (!isset($is_new)) {
            if ($user->num <= 0) {
                return Json::encode(['msg' => '今天投票次数已经够了!', 'state' => 3]);
            }
            //判断当前是否重复对某个营业厅重复投票
            if ($vote_arr = Vote::getTodayVote($login_arr['id'])){
                if (in_array($id,$vote_arr)) {
                    return Json::encode(['msg' => '不能对一个营业厅重复投票' , 'state' => 4]);
                }
            }
            //投票数量减一
            $user->num -= 1;
            if (!($user->save())){
                return Json::encode(['msg' => '今天投票次数已经够了!', 'state' => 3]);
            }
        }

        //投票前加锁
        if(!$this->lock('vote')
                //redis内部数据加1
            || !($num = redis()->zincrby('hall',1,$id)) ) {
           return Json::encode(['msg' => '投票失败', 'state' => 2]);
        }
        //记录redis投票数量
        //记录投票关系
        if (!(Vote::addVoteRelation($id,$user_obj->id))){
            return Json::encode(['msg' => '投票失败', 'state' => 2]);
        }

        //添加营业厅投票数量
        $hall->num = $num;
        if (!$hall->save()){
            return Json::encode(['msg' => '投票失败', 'state' => 2]);
        }
        $this->unlock('vote');
        return Json::encode(['msg' => '投票成功', 'state' => 1]);
    }

    /**
     * 我的剩余投票次数
     * @desc 使用get方式 请使用  &quot;&lt;?php echo \yii\helpers\Url::to(['myvotenumapi'])?&gt;&quot;
     * @return int state 操作码，0表示非法请求; 1表示查询成功
     * @return string msg 提示信息
     * @return int num 剩余的数量
     * @exception 400 参数传递错误
     * @exception 500 服务器内部错误
     */
    public function actionMyvotenumapi(){
        $request = \Yii::$app->getRequest();
        if (!($login_arr = $this->loginInfo($this->login_key)) || !$request->isAjax || !$request->isGet ) {
            return Json::encode(['msg' => '非法请求', 'state' => 0]);
        }

        return Json::encode(['num' => (int)(User::getMyNum($login_arr['openid'])) , 'state' => 1]);
    }
}