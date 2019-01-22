<?php
/**
 * Created by date 2018/10/16.
 * Author: wei.sun
 * Type:
 ***/

namespace Home\Controller;


use Home\Model\DiscoverNoticeModel;
use Home\Model\StreamInfoModel;

class DiscoversController extends BaseController
{
    public function getdiscover()
    {
        $this->display();
    }

    public function getContent()
    {
        $DiscoverNoticeModel = new DiscoverNoticeModel();
        $map["status"] = 1;
        $result = $DiscoverNoticeModel->getDiscoverNotice($map);
        $this->result = $result;
        $page = "Discovers/getcontent";
        $data = $this->fetch($page);
        $this->ajaxReturn($data);
    }

    public function getSendDiscover()
    {

        $page = "Discovers/send_discover";
        $data = $this->fetch($page);
        $this->ajaxReturn($data);
    }

    public function getEditContent()
    {
        $DiscoverNoticeModel = new DiscoverNoticeModel();
        $model = new StreamInfoModel();
        $map["status"] = 1;
        $map["notice_id"] = I("msg_id");
        $result = $DiscoverNoticeModel->getDiscoverNotice($map);

        $rowkey = str_replace(".html", "", $result[0]["content_url"]);
        $content = $model->getMediaDetail($rowkey);
        $this->result = $result;
        $this->content = $content;
        $page = "Discovers/geteditdiscover";
        $data = $this->fetch($page);
        $this->ajaxReturn($data);
    }

    public function updateDiscover()
    {
        $id = I("id");
        $status = I("status");
        $rowkey = I("rowkey");
        $DiscoverNoticeModel = new DiscoverNoticeModel();
        $StreamInfoModel = new StreamInfoModel();
        if ($status == 0) {
            $map["notice_id"] = $id;
            $arr["status"] = $status;

            $DiscoverNoticeModel->updateDiscoverNotice($map, $arr);
            $DiscoverNoticeModel->updateDiscoverNoticeLoop($map, $arr);
        } else {
            $map["rowkey"] = $rowkey;
            $rwap["content_url"] = $rowkey . ".html";
            $arr["title"] = trim(I("title"));
            $thumbnail = array_filter(I('thumbnail'));
            $arr["thumbnail"] = $thumbnail[0];
            $arr["pic_url"] = $thumbnail[0];

            $arr["media_time"] = trim(I("publish_time"));
            $arr["publish_time"] = trim(I("publish_time"));
            $content = htmlspecialchars_decode(I("content"));
            preg_match_all("<img.*?src=\"(.*?.*?)\".*?>", $content, $match);
            foreach ($match[1] as $val) {
                $imgsrc[] = basename($val);
            }
            $content = str_replace($this->replaceRPath, C("replaceYPath"), $content);
            $arr["content"] = $content;

            $DiscoverNoticeModel->updateDiscoverNotice($rwap, $arr);
            $StreamInfoModel->updateMediaDetail($map, $arr);
        }
    }


    public function send_discover()
    {
        $this->display();
    }

    public function banner_iscover()
    {

        $DiscoverNoticeModel = new DiscoverNoticeModel();
        $wap["status"]=1;
        $result = $DiscoverNoticeModel->getDiscoverNotice($wap);
        foreach ($result as $key => $val) {
            $map["notice_id"] = $val["notice_id"];
            $status = $DiscoverNoticeModel->getDiscoverNoticeLoop($map);
            $result[$key]["loop_status"] = $status[0]["status"];
        }

        $this->assign("result", $result);
        $this->display();
    }


    private $send = "发布";
    private $replaceRPath = '/qiguan/discover/';


    public function addDiscover()
    {
        $session = session("qg_auth");
        $arr["user_id"] = $session[0]['user_id'];

        //标题
        $arr["title"] = trim(I("title"));
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
        $thumbnail = array_filter(I('thumbnail'));
        if (!empty($thumbnail)) {
            $arr["thumbnail_url"] = $thumbnail[0];
        }

        $arr["layout"] = $this->send;
        $arr["media_time"] = $arr["publish_time"];
        $arr["media_type"] = "discover";
        $arr["rowkey"] = getKey($arr["title"] . $arr["publish_time"] . $arr["msg_id"]);
        $arr["details_url"] = $arr["rowkey"] . '.html';
        //添加内容库
        $StreamInfoModel = new StreamInfoModel();
        $StreamInfoModel->addMediaDetail($arr);

        //添加内容库
        $DiscoverNoticeModel = new DiscoverNoticeModel();
        $map["title"] = $arr["title"];
        $map["pic_url"] = $arr["thumbnail_url"];
        $map["content_url"] = $arr["details_url"];
        $map["publish_time"] = $arr["publish_time"];
        $map["status"] = 1;

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