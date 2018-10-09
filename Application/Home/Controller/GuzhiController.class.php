<?php

namespace Home\Controller;

use Home\Model\ChannelInfoModel;
use Home\Model\StreamInfoModel;
use Think\Controller;

class GuzhiController extends BaseController
{


    //上传路径
    private $path = "./Uploads/";
    private $jx_images = "jx_images/";


    /*****
     *精选
     *  $cacheKey:定义唯一缓存key值
     ****/
    public function jingxuan()
    {
        $StreamInfoModel = new StreamInfoModel();
        $map["guanzhi_type"]="zt";
        $result=$StreamInfoModel->getGuzhiInfor($map);
        $this->assign("result",$result);
        $this->display();
    }

    public function addJXMethod()
    {
        $arr["title"] = trim(I("title"));
        //$arr["summary"] = htmlspecialchars(I("summary"));
        $arr["guanzhi_desc"] = htmlspecialchars_decode(I("desc"));
        $arr["thumnailChannel"] = htmlspecialchars_decode(I("thumnailChannel"));
        $thumbnail = array($_FILES['thumbnail']);

        $session = session("qg_auth");
        $arr["user_id"] = $session[0]['user_id'];
        $arr["guanzhi_type"] = trim(I("channel"));

        $arr["publish_time"] = Date("Y-m-d H:i:s");

        if (!empty($thumbnail[0]['name'][$arr["thumnailChannel"]])) {
            $arr["pic_url"] = $thumbnail[0]['name'][$arr["thumnailChannel"]];
            $arr["final_content"] = $thumbnail[0]['name'][$arr["thumnailChannel"]];
            $this->uploadMVI($thumbnail, $this->path, $this->jx_images);
        }

        $StreamInfoModel = new StreamInfoModel();
        $StreamInfoModel->addGuzhiInfo($arr);
    }



    public function deltedMethod(){
        $StreamInfoModel = new StreamInfoModel();
        $map["guanzhi_id"] = I("id");
        $result = $StreamInfoModel->getGuzhiInfor($map);
        if (!empty($result)) {
            $StreamInfoModel->deletedGuzhiAction($map);
        }
    }

    /*****
     *游历
     *  $cacheKey:定义唯一缓存key值
     ****/
    public function youli()
    {
        $StreamInfoModel = new StreamInfoModel();
        $map["guanzhi_type"]="yl";
        $result=$StreamInfoModel->getGuzhiInfor($map);
        $this->assign("result",$result);
        $this->display();
    }

    /*****
     *问道
     *  $cacheKey:定义唯一缓存key值
     ****/
    public function wendao()
    {
        $StreamInfoModel = new StreamInfoModel();
        $map["guanzhi_type"]="wd";
        $result=$StreamInfoModel->getGuzhiInfor($map);
        $this->assign("result",$result);
        $this->display();
    }

    /*****
     *博览
     *  $cacheKey:定义唯一缓存key值
     ****/
    public function bolan()
    {
        $StreamInfoModel = new StreamInfoModel();
        $map["guanzhi_type"]="bl";
        $result=$StreamInfoModel->getGuzhiInfor($map);
        $this->assign("result",$result);
        $this->display();
    }
}

?>