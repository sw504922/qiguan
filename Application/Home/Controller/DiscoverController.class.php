<?php
/**
 * Created by date 2018/10/16.
 * Author: wei.sun
 * Type:
 ***/

namespace Home\Controller;


use Home\Model\DiscoverNoticeModel;
use Home\Model\StreamInfoModel;

class DiscoverController extends BaseController
{
    public function send_discover()
    {
        $this->display();
    }

    public function banner_iscover()
    {

        $DiscoverNoticeModel = new DiscoverNoticeModel();
        $result = $DiscoverNoticeModel->getDiscoverNotice("");
        foreach($result as $key=>$val){
            $map["notice_id"] = $val["notice_id"];
            $status=$DiscoverNoticeModel->getDiscoverNoticeLoop($map);
            $result[$key]["loop_status"]=$status[0]["status"];
        }

        $this->assign("result", $result);
        $this->display();
    }



    private $send = "发布";
    private $replaceRPath = '/qiguan/Uploads/tw_images/';
    private $path = "./Uploads/";
    private $discover = "discover/";


    public function addDiscover(){
        $session = session("qg_auth");
        $arr["user_id"] = $session[0]['user_id'];

        //标题
        $arr["title"]=trim(I("title"));
        //内容
        $content = htmlspecialchars_decode(I("content"));
        preg_match_all("<img.*?src=\"(.*?.*?)\".*?>", $content, $match);
        foreach ($match[1] as $val) {
            $imgsrc[] = basename($val);
        }
        $content = str_replace($this->replaceRPath, C("replaceYPath"), $content);
        $arr["content"] = $content;

        //发布时间
        $arr["publish_time"] = trim(I("publish_time"));
        if ($arr["publish_time"] == 0) {
            $arr["publish_time"] = date("Y-m-d H:i:s");
        }
        //缩略图
        $thumbnail = array($_FILES['thumbnail']);
        if (!empty($thumbnail[0]['name'])) {
            $arr["thumbnail_url"] = $thumbnail[0]['name'][0];
            $this->uploadMVI($thumbnail, $this->path, $this->discover);
        }



        $arr["layout"] = $this->send;
        $arr["media_time"] = $arr["publish_time"];
        $arr["media_type"] = "discover";
        $arr["rowkey"] = getKey($arr["title"] . $arr["publish_time"] . $arr["msg_id"]);
        $arr["details_url"]=$arr["rowkey"].'.html';

        //添加内容库
        $StreamInfoModel = new StreamInfoModel();
        $StreamInfoModel->addMediaDetail($arr);

        //添加内容库
        $DiscoverNoticeModel = new DiscoverNoticeModel();
        $map["title"]=$arr["title"];
        $map["pic_url"]=$arr["thumbnail_url"];
        $map["content_url"]=$arr["details_url"];
        $map["publish_time"]=$arr["publish_time"];
        $map["status"]=1;
        $DiscoverNoticeModel->addDiscoverNotice($map);
    }


    public function setStatusBanner()
    {
        $arr["status"] = I("status");
        $map["notice_id"] = I("id");
        $DiscoverNoticeModel = new DiscoverNoticeModel();
        $result = $DiscoverNoticeModel->getDiscoverNoticeLoop($map);

        if (empty($result) && $arr["status"] == 1) {
            $arr["notice_id"] = $map["notice_id"];
            $DiscoverNoticeModel->addDiscoverNoticeLoop($arr);
        } else {
            $DiscoverNoticeModel->updateDiscoverNoticeLoop($map, $arr);
        }


    }

}