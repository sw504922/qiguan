<?php
/**
 * Created by PhpStorm.
 * User: wei.sun
 * Date: 2017/8/14
 * Time: 11:43
 */

namespace Home\Controller;
use Think\Auth;
use Think\Controller;
use Home\Model\ChannelInfoModel;
use Think\Log;
class BaseController extends Controller
{
    protected function _initialize(){
        $session=session("od_auth");

        if(empty($session)){
            $this->redirect("Login/login");
        }

        $value=$session[0];
        $uid = $value['id'];
        $auth = new Auth();
        if (! $auth->check(MODULE_NAME . '/' . CONTROLLER_NAME , $uid)) {
            $this->redirect('Login/login');
        }

        $this->list=$value;
        $auth = new Auth();
        $uid=$value["id"];
        $model=M("auth_rule");
        $result=$model->select();

        foreach ($result as $val){
            if ($auth->check( $val["name"], $uid)) {
                $length=strripos($val["name"],"/");
                $su=substr($val["name"],$length+1,strlen($val["name"]));
                $this->$su= $val["id"];
            }
        }



        $process=array("空白","接听","挂断","待跟进");
        $arr["auth"]=$session[0];
        $arr["endDate"] = Date("Y-m-d", strtotime("+1 days"));
        $arr["startDate"] = Date("Y-m-d", strtotime("-3 month"));
        $channelModel = new ChannelInfoModel();
        $channel = $channelModel->getChannelType();
        $getParent = $this->getParent();
        $getManger = $channelModel->getManger();
        $getConversion = $channelModel->getConversion();
        $this->assign("arr", $arr);
        $this->assign("channel", $channel);
        $this->assign("parent", $getParent);
        $this->assign("manger", $getManger);
        $this->assign("conversion", $getConversion);
        $this->assign("process", $process);



    }


    /**
     * get exchange rate
     ***/
    public function getCoinToHK(){
        $nowDate = Date("Y-m-d");
        $huilvCache = file_get_contents("http://api.k780.com/?app=finance.rate&scur=USD&tcur=HKD&appkey=10003&sign=b59bc3ef6191eb9f747dd4e83c99f2a4");
        $CNYCache = file_get_contents("http://api.k780.com/?app=finance.rate&scur=CNY&tcur=HKD&appkey=10003&sign=b59bc3ef6191eb9f747dd4e83c99f2a4");
        S($nowDate."US", $huilvCache, 3600 * 60 * 24);
        S($nowDate."CN", $CNYCache, 3600 * 60 * 24);
    }


    public function getParent()
    {
        $channelModel = new ChannelInfoModel();
        $getParent = $channelModel->getParent();
        return $getParent;
    }


    /**
     * upload imgage
     ***/
    public function  getBannerImage($Thumbail,$path,$path_subName){
        foreach($Thumbail as $img) {
            $name = $img['name'];
        }
        log::write($path_subName.$path.$name." is thunmail name");
        $upload = new \Think\Upload();
        $upload->maxSize = 3145728;
        $upload->exts = array(
            'jpg',
            'gif',
            'png',
            'jpeg'
        );
        $upload->rootPath = $path;
        $upload->subName = $path_subName;
        $upload->saveName = '';
        $static_thum = $upload->upload($Thumbail);

    }
}