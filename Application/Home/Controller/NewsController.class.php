<?php

namespace Home\Controller;

use Home\Model\ChannelInfoModel;
use Home\Model\StreamInfoModel;
use Think\Controller;

class NewsController extends BaseController
{


    /***
     * display Area
     ***/
    public function newsShow()
    {
        $this->display("news");
    }

    public function photo()
    {
        $this->display();
    }

    public function music()
    {
        $this->display();
    }

    public function video()
    {
        $this->display();
    }

    public function content()
    {
        $this->display();
    }

    public function report()
    {
        $this->display();
    }

    public function comments()
    {
        $this->display();
    }






    /*
     *
     *
     * */

    //上传路径
    private $path="./Uploads/";
    private $tw_images="tw_images/";
    private $pics_images="pics_images/";
    private $special_subName="special_images/";
    private $jrqgChannel=array("精选"=>"1","闻道"=>"2","博览"=>"3","游历"=>"4","回忆"=>"5");

    /*****
     *图文内容
     *  $cacheKey:定义唯一缓存key值
     ****/
    private $news="news";
    private $pics="pics";
    private $radio="radio";
    private $vedio="vedio";
    private $send="发布";


    public function addImgAndContent(){
        $session = session("qg_auth");
        $arr["user_id"]= $session[0]['user_id'];
        $arr["title"]=trim(I("title"));
        $arr["msg_abstract"]=htmlspecialchars_decode(I("summary"));
        $arr["content"]=htmlspecialchars_decode(I("content"));
        $thumbnail = array($_FILES['thumbnail']);

        $arr["send"]=trim(I("send"));
        $arr["channel"]=trim(I("channel"));
        $arr["media_type"]=$this->news;
        if(!empty($thumbnail[0]['name'])) {
           // $arr["thumbnail_url"] = implode(";",$thumbnail[0]['name']);
            $arr["thumbnail_url"] = $thumbnail[0]['name'][0];
            $status = $this->uploadMVI($thumbnail,$this->path,$this->tw_images);
        }

        $StreamInfoModel=new StreamInfoModel();

        $msgID=$StreamInfoModel->addStreamAction($arr);

        $arr["summary"]=$arr["msg_abstract"];
        $arr["msg_id"]=$msgID;
        $arr["layout"]=$this->send;
        $arr["tags"]="测试";

        $date=Date("Y-m-d H:i:s");
        $arr["rowkey"]=getKey($arr["title"].$date.$arr["msg_id"]);
        $StreamInfoModel->addMediaDetail($arr);

        $mediaId=$StreamInfoModel->getMediaDetail($arr["rowkey"]);
        $map["msg_id"]=$msgID;
        $map["media_id"]=$mediaId;
        $map["is_ad"]=0;
        $map["overview_pic"]=$arr["thumbnail_url"];

        $StreamInfoModel->addStreamMedia($map);

    }






    public function addSetPic(){
        $session = session("qg_auth");
        $arr["user_id"]= $session[0]['user_id'];
        $arr["title"]=trim(I("title"));
        $arr["msg_abstract"]=htmlspecialchars_decode(I("summary"));

        $thumbnail = array($_FILES['thumbnail']);
        $upload_pic = array($_FILES['upload_pic']);

        $arr["send"]=trim(I("send"));
        $arr["channel"]=trim(I("channel"));
        $arr["media_type"]=$this->pics;
        if(!empty($thumbnail[0]['name'])) {
            $arr["thumbnail_url"] = $upload_pic[0]['name'];
            $this->uploadMVI($thumbnail,$this->path,$this->tw_images);
            $this->uploadMVI($upload_pic,$this->path,$this->pics_images);
        }

        $StreamInfoModel=new StreamInfoModel();

        $msgID=$StreamInfoModel->addStreamAction($arr);

        $arr["summary"]=$arr["msg_abstract"];
        $arr["msg_id"]=$msgID;
        $arr["layout"]=$this->send;

        $date=Date("Y-m-d H:i:s");
        $arr["rowkey"]=getKey($arr["title"].$date.$arr["msg_id"]);
        $StreamInfoModel->addMediaDetail($arr);

        $mediaId=$StreamInfoModel->getMediaDetail($arr["rowkey"]);
        $map["msg_id"]=$msgID;
        $map["media_id"]=$mediaId;
        $map["is_ad"]=0;
        $map["overview_pic"]=$arr["thumbnail_url"];

        $StreamInfoModel->addStreamMedia($map);

    }







    /*****
     *精选
     *  $cacheKey:定义唯一缓存key值
     ****/
    public function jingxuan()
    {
        $StreamInfoModel=new StreamInfoModel();

        $result=$StreamInfoModel->getChannel($this->jrqgChannel["精选"]);
        $resultCount=$StreamInfoModel->getChannel($this->jrqgChannel["精选"]);
        $this->assign("result",$result);
        $this->assign("resultCount",$resultCount);
        $this->display();
    }

    public function addJXMethod(){
        $arr["title"]=trim(I("title"));
        $arr["summary"]=htmlspecialchars(I("summary"));
        $arr["msg_abstract"]=htmlspecialchars_decode(I("desc"));
        $arr["thumnailChannel"]=htmlspecialchars_decode(I("thumnailChannel"));
        $thumbnail = array($_FILES['thumbnail']);

        $session = session("qg_auth");
        $arr["user_id"]= $session[0]['user_id'];
        $arr["channel"]=trim(I("channel"));

        $arr["publish_time"]=Date("Y-m-d H:i:s");

        if(!empty($thumbnail[0]['name'])) {
            $arr["thumbnail_url"] = array($thumbnail[0]['name']);
            $status = $this->uploadMVI($thumbnail,$this->path,$this->special_subName);
        }
        $StreamInfoModel=new StreamInfoModel();
        $StreamInfoModel->addStreamAction($arr);
    }

    public function deltedMethod(){
        $StreamInfoModel=new StreamInfoModel();
        $map["msg_id"]=I("id");
        $result=$StreamInfoModel->getStreamInfo($map);
        if(!empty($result)){
            $StreamInfoModel->deletedAction($map);
        }
    }

    public function updateStatusMethod(){
        $StreamInfoModel=new StreamInfoModel();
        $map["msg_id"]=I("id");
        $arr["status"]=I("status");

        $result=$StreamInfoModel->getStreamInfo($map);
        if(!empty($result)){
            $StreamInfoModel->updateAction($map,$arr);
        }
    }


    /*****
     *游历
     *  $cacheKey:定义唯一缓存key值
     ****/
    public function youli()
    {
        $StreamInfoModel=new StreamInfoModel();
        $result=$StreamInfoModel->getChannel($this->jrqgChannel["游历"]);
        $resultCount=$StreamInfoModel->getChannel($this->jrqgChannel["游历"]);
        $this->assign("result",$result);
        $this->assign("resultCount",$resultCount);
        $this->display();
    }
    /*****
     *问道
     *  $cacheKey:定义唯一缓存key值
     ****/
    public function wendao()
    {
        $StreamInfoModel=new StreamInfoModel();

        $result=$StreamInfoModel->getChannel($this->jrqgChannel["闻道"]);
        $resultCount=$StreamInfoModel->getChannel($this->jrqgChannel["闻道"]);
        $this->assign("result",$result);
        $this->assign("resultCount",$resultCount);
        $this->display();
    }
    /*****
     *博览
     *  $cacheKey:定义唯一缓存key值
     ****/
    public function bolan()
    {
        $StreamInfoModel=new StreamInfoModel();

        $result=$StreamInfoModel->getChannel($this->jrqgChannel["博览"]);
        $resultCount=$StreamInfoModel->getChannel($this->jrqgChannel["博览"]);
        $this->assign("result",$result);
        $this->assign("resultCount",$resultCount);
        $this->display();
    }
}

?>