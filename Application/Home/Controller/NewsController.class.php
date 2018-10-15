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
    public function newsshow()
    {
        $this->display();
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


    //上传路径
    private $path = "./Uploads/";
    private $tw_images = "tw_images/";
    private $pics_images = "pics_images/";
    private $video_images = "video_images/";
    private $music_images = "music_images/";
    private $special_subName = "special_images/";
    private $jrqgChannel = array("精选" => "1", "闻道" => "2", "博览" => "3", "游历" => "4", "回忆" => "5");
    private $newsToGunzhiChannel = array("1" => "zt", "2" => "wd", "4" => "bl", "5" => "yl", "6" => "hy");
    private $mediaType = array("article" => "文章", "pics" => "图集", "audio" => "音频", "radio" => "视频");
    private $news = "article";
    private $pics = "pics";
    private $music = "audio";
    private $vedio = "radio";
    private $send = "发布";
    private $limit = "20";
    private $noGuanzhi = "暂无观止频道数据";
    private $replaceRPath = '/qiguan/Uploads/tw_images/';
    private $replaceYPath = 'http://47.92.45.8/qiguan/Uploads/tw_images/';


    public function addImgAndContent()
    {
        $session = session("qg_auth");
        $arr["user_id"] = $session[0]['user_id'];
        $arr["title"] = trim(I("title"));
        $arr["msg_abstract"] = htmlspecialchars_decode(I("summary"));
        $thumbnail = array($_FILES['thumbnail']);
        $arr["send"] = trim(I("send"));
        $arr["channel"] = trim(I("channel"));
        $arr["media_type"] = $this->news;
        $arr["publish_time"] = trim(I("publish_time"));
        if ($arr["publish_time"] == 0) {
            $arr["publish_time"] = date("Y-m-d H:i:s");
        }


        if (!empty($thumbnail[0]['name'])) {
            $arr["thumbnail_url"] = $thumbnail[0]['name'][0];
            $this->uploadMVI($thumbnail, $this->path, $this->tw_images);
        }

        $StreamInfoModel = new StreamInfoModel();


        $msgID = $StreamInfoModel->addStreamAction($arr);

        $arr["msg_id"] = $msgID;
        $arr["layout"] = $this->send;
        $arr["media_time"] = $arr["publish_time"];
        $arr["summary"] = $arr["msg_abstract"];
        $content = htmlspecialchars_decode(I("content"));
        preg_match_all("<img.*?src=\"(.*?.*?)\".*?>", $content, $match);
        foreach ($match[1] as $val) {
            $imgsrc[] = basename($val);
        }
        $content = str_replace($this->replaceRPath, $this->replaceYPath, $content);
        $arr["content"] = $content;
        $arr["rowkey"] = getKey($arr["title"] . $arr["publish_time"] . $arr["msg_id"]);
        $StreamInfoModel->addMediaDetail($arr);


        //StreamMedia
        $this->addStreamMedia($arr);
        //GuanzhiMsg
        $arr["guanzhi_id"] = trim(I("guanzhi_id"));
        if ($arr["guanzhi_id"] != $this->noGuanzhi) {
            $this->addGuanzhiMsg($arr);
        }


    }


    public function addSetPic()
    {
        $session = session("qg_auth");
        $arr["user_id"] = $session[0]['user_id'];
        $arr["title"] = trim(I("title"));
        $arr["msg_abstract"] = htmlspecialchars_decode(I("summary"));
        $upload_pic = array($_FILES['thumbnail']);
        $thumbnail = array($_FILES['upload_pic']);


        $picdesc = I('picdesc');
        $arr["send"] = trim(I("send"));
        $arr["publish_time"] = trim(I("publish_time"));
        $arr["guanzhi_id"] = trim(I("guanzhi_id"));
        if ($arr["publish_time"] == 0) {
            $arr["publish_time"] = date("Y-m-d H:i:s");
        }
        $arr["channel"] = trim(I("channel"));
        $arr["media_type"] = $this->pics;

        if (!empty($thumbnail[0]['name'])){
            $arr["thumbnail_url"] = $thumbnail[0]['name'];
            $this->uploadMVI($thumbnail, $this->path, $this->pics_images);
        }

        if (!empty($upload_pic[0]['name'])) {
            $this->uploadMVI($upload_pic, $this->path, $this->pics_images);
        }
        $arr["layout"] = $this->send;
        $arr["media_time"] = $arr["publish_time"];

        $StreamInfoModel = new StreamInfoModel();
        $opinion_method = I("opinion_method");
        if ($opinion_method != "update") {
            $msgID = $StreamInfoModel->addStreamAction($arr);
            $arr["msg_id"] = $msgID;

            //MediaDetail
            foreach ($upload_pic[0]['name'] as $key => $val) {
                if (!empty($val)) {
                    $arr["rowkey"] = getKey($arr["title"] . $arr["publish_time"] . $arr["msg_id"] . $picdesc[$key]);
                    $mediaDetailThumName = $val;
                    $mediaDetailDesc = $picdesc[$key];
                    $arr["summary"] = $mediaDetailDesc;
                    $arr["content"] = $mediaDetailThumName;
                    $StreamInfoModel->addMediaDetail($arr);

                    //StreamMedia
                    $this->addStreamMedia($arr);
                }
            }

            //GuanzhiMsg
            if ($arr["guanzhi_id"] != $this->noGuanzhi) {
                $this->addGuanzhiMsg($arr);
            }
        } else {

            $map["msg_id"] = I("msg_id");
            $result = $StreamInfoModel->getStreamInfo($map);

            if ($result[0]["title"] != $arr["title"] || $result[0]["msg_abstract"] != $arr["msg_abstract"]
                || $result[0]["channel"] != $arr["channel"] || $result[0]["publish_time"] != $arr["publish_time"] || !empty($arr["thumbnail_url"])) {
                $StreamInfoModel->updateStreamInfo($map, $arr);
            }

            if (!empty($arr["thumbnail_url"])){
                $arr["overview_pic"]=$arr["thumbnail_url"];
                $StreamInfoModel->updateStreamMedia($map, $arr);
            }

            $updateID = $StreamInfoModel->getStreamMedia($map);

            foreach ($picdesc as $key => $val) {
                if (!empty($val)) {
                    $arr["summary"] = $val;
                    $wmap["rowkey"] = $updateID[$key]['final_content'];
                    if (!empty($val)) {
                        $StreamInfoModel->updateMediaDetail($wmap, $arr);
                    }
                }
            }

        }
    }


    public function addVideo()
    {
        $session = session("qg_auth");
        $arr["user_id"] = $session[0]['user_id'];

        $arr["title"] = trim(I("title"));
        $arr["type"] = trim(I("sendtype"));
        $arr["msg_abstract"] = htmlspecialchars_decode(I("summary"));
        $arr["send"] = trim(I("send"));
        $arr["publish_time"] = trim(I("publish_time"));
        if ($arr["publish_time"] == 0) {
            $arr["publish_time"] = date("Y-m-d H:i:s");
        }
        $arr["channel"] = trim(I("channel"));
        $arr["thumnailChannel"] = trim(I("thumnailChannel"));


        $thumbnail = array($_FILES['thumbnail']);
        $upload_music = array($_FILES['upload_music']);


        if (!empty($thumbnail[0]['name'][$arr["thumnailChannel"]])) {
            $arr["thumbnail_url"] = $thumbnail[0]['name'][$arr["thumnailChannel"]];
            if ($arr["type"] == $this->vedio) {
                $arr["media_type"] = $this->vedio;
                $this->uploadMVI($thumbnail, $this->path, $this->video_images);
                $this->uploadMVI($upload_music, $this->path, $this->video_images);
            } else {
                $arr["media_type"] = $this->music;
                $this->uploadMVI($thumbnail, $this->path, $this->music_images);
                $this->uploadMVI($upload_music, $this->path, $this->music_images);
            }

        }

        //Stream
        $StreamInfoModel = new StreamInfoModel();
        $msgID = $StreamInfoModel->addStreamAction($arr);

        //mediaDetail
        $arr["msg_id"] = $msgID;
        $arr["layout"] = $this->send;
        $arr["media_time"] = $arr["publish_time"];
        $arr["summary"] = $arr["msg_abstract"];
        $arr["rowkey"] = getKey($arr["title"] . $arr["publish_time"] . $arr["msg_id"]);
        $arr["content"] = $upload_music[0]['name'];
        $StreamInfoModel->addMediaDetail($arr);


        //StreamMedia
        $this->addStreamMedia($arr);

        //GuanzhiMsg
        $arr["guanzhi_id"] = trim(I("guanzhi_id"));
        if ($arr["guanzhi_id"] != $this->noGuanzhi) {
            $this->addGuanzhiMsg($arr);
        }
    }


    /***
     *
     * 添加Stream与文章关联信息
     ****/
    public function addStreamMedia($arr)
    {
        $StreamInfoModel = new StreamInfoModel();
        $mediaId = $StreamInfoModel->getMediaDetail($arr["rowkey"]);
        foreach ($mediaId as $value) {
            $map["msg_id"] = $arr["msg_id"];
            $map["final_content"] = $value["rowkey"];
            $map["is_ad"] = 0;
            $map["overview_pic"] = $arr["thumbnail_url"];
            $StreamInfoModel->addStreamMedia($map);
        }
    }

    /***
     *
     * 添加观止频道与文章关联信息
     ****/
    public function addGuanzhiMsg($arr)
    {
        $map["guanzhi_id"] = $arr["guanzhi_id"];
        $map["msg_id"] = $arr["msg_id"];
        $StreamInfoModel = new StreamInfoModel();
        $StreamInfoModel->addGuanzhiMsg($map);
    }

    /****
     * 获取观止新闻频道
     ****/
    public function getGuzhiChannel()
    {
        $channel = trim(I("channel"));
        $guzhiChannel = $this->newsToGunzhiChannel[$channel];
        if (!empty($guzhiChannel)) {
            $map["guanzhi_type"] = $guzhiChannel;
        }
        $StreamInfoModel = new StreamInfoModel();
        $this->result = $StreamInfoModel->getGuzhiInfor($map);
        $data = $this->fetch("News/guzhi_type");
        $this->ajaxReturn($data);
    }


    /*****
     *内容库管理获取内容
     ****/
    public function getContent()
    {
        $session = session("qg_auth");
        $arr["user_id"] = $session[0]['user_id'];
        $type = I("status");
        $new_page = I('new_page');
        if ($new_page == 0) {
            $new_page = 1;
        }
        $offset = ($new_page - 1) * $this->limit;

        $StreamInfoModel = new StreamInfoModel();
        $dataResult = $StreamInfoModel->getChannel($type, $arr["user_id"], $offset, $this->limit);

        foreach ($dataResult as $val) {
            $val["ch_media_type"] = $this->mediaType[$val["media_type"]];
            $result[] = $val;
        }
        $this->result = $result;
        $this->resultCount = $StreamInfoModel->getChanneCount($type, $arr["user_id"]);
        $this->new_page = $new_page;
        $this->viewCount = $this->limit;
        $data = $this->fetch("News/get_content");
        $this->ajaxReturn($data);
    }

    /*****
     *内容库管理删除操作
     ****/
    public function deltedMethod()
    {
        $StreamInfoModel = new StreamInfoModel();
        $map["msg_id"] = I("id");
        $result = $StreamInfoModel->getStreamInfo($map);
        if (!empty($result)) {
            $StreamInfoModel->deletedAction($map);
        }
    }

    /*****
     *内容库管理隐藏操作
     ****/
    public function updateStatusMethod()
    {
        $StreamInfoModel = new StreamInfoModel();
        $map["msg_id"] = I("id");
        $arr["status"] = I("status");

        $result = $StreamInfoModel->getStreamInfo($map);
        if (!empty($result)) {
            $StreamInfoModel->updateAction($map, $arr);
        }
    }


    public function getUpdateNews()
    {
        $map["msg_id"] = I("msg_id");
        $StreamInfoModel = new StreamInfoModel();

        $streamMedia = $StreamInfoModel->getStreamMedia($map);
        $streamInfo = $StreamInfoModel->getStreamInfo($map);
        $guZhiMsg = $StreamInfoModel->getGuanzhiMsg($map);
        foreach ($streamMedia as $val) {
            $mediaDetailResult = $StreamInfoModel->getMediaDetail($val["final_content"]);
            $mediaDetailArr[] = $mediaDetailResult[0];
        }

        $mediaDetail = $mediaDetailArr;

        $result["mediaDetail"] = $mediaDetail;
        $result["streamMedia"] = $streamMedia;
        $result["streamInfo"] = $streamInfo;
        $result["guZhiMsg"] = $guZhiMsg;

        $this->assign("result", $result);
        $this->display("update_news");
    }

}

?>