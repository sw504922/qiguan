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
    public function getarticle()
    {

        $this->display();
    }

    public function getpics()
    {

        $this->display();
    }

    public function getaudio()
    {

        $this->display();
    }

    public function getradio()
    {
        $this->display();
    }

    public function report()
    {
        $result["guanzhi_id"] = I("guanzhiid");
        $result["channel"] = I("channel");
        $this->assign("result", $result);
        $this->display();
    }

    public function comments()
    {
        $result["guanzhi_id"] = I("guanzhiid");
        $result["channel"] = I("channel");
        $this->assign("result", $result);
        $this->display();
    }

    /*****
     *内容库管理获取内容
     ****/

    public function getContent()
    {
        $session = session("qg_auth");
        $user_id = $session[0]['user_id'];
        $type = I("status");
        $media_type = I("media_type");
        $new_page = I('new_page');

        if ($new_page == 0 || empty($new_page)) {
            $new_page = 1;
        }

        $offset = ($new_page - 1) * $this->limit;

        $StreamInfoModel = new StreamInfoModel();
        $result = $StreamInfoModel->getChannel($type, $user_id, $media_type, $offset, $this->limit);
        $resultcount = $StreamInfoModel->getChanneCount($type, $user_id, $media_type);


        $this->result = $result;
        $this->newsurl = C("newsurl");
        $this->resultCount = $resultcount;
        $this->new_page = $new_page;
        $this->viewCount = $this->limit;
        $data = $this->fetch("News/get_content");
        $this->ajaxReturn($data);
    }


    public function getAddMethod()
    {
        $media_type = I("media_type");
        $page = "News/add_" . $media_type;
        $data = $this->fetch($page);
        $this->ajaxReturn($data);
    }


    /*****
     *@获取更新新闻
     ****/
    public function getUpdateNews()
    {
        $map["msg_id"] = I("msg_id");
        $StreamInfoModel = new StreamInfoModel();

        $streamMedia = $StreamInfoModel->getStreamMedia($map);
        $streamInfo = $StreamInfoModel->getStreamInfo($map);
        $guZhiMsg = $StreamInfoModel->getGuanzhiMsg($map);
        $tagMsg = $StreamInfoModel->getTagMedia($map);
        foreach ($streamMedia as $val) {
            $mediaDetailResult = $StreamInfoModel->getMediaDetail(strReplace($val["final_content"]));
            $mediaDetailArr[] = $mediaDetailResult[0];
        }
        $mediaDetail = $mediaDetailArr;
        $result["mediaDetail"] = $mediaDetail;
        $result["streamMedia"] = $streamMedia;
        $result["streamInfo"] = $streamInfo;
        $result["guZhiMsg"] = $guZhiMsg;
        $result["tagMsg"] = $tagMsg;

        $this->assign("result", $result);
        $media_type = $result['streamInfo'][0]['media_type'];
        if ($media_type == "pics") {
            $page = "update_pics";
        } else if ($media_type == "audio") {
            $page = "update_audio";
        } else if ($media_type == "radio") {
            $page = "update_radio";
        } else if ($media_type == "article") {
            $page = "update_article";
        }
        $data = $this->fetch($page);
        $this->ajaxReturn($data);
    }

    //上传路径
    private $path = "./discover/";
    private $jrqgChannel = array("精选" => "1", "闻道" => "2", "博览" => "3", "游历" => "4", "回忆" => "5");
    private $newsToGunzhiChannel = array("1" => "zt", "2" => "wd", "3" => "bl", "4" => "yl", "5" => "hy");
    private $mediaType = array("article" => "文章", "pics" => "图集", "audio" => "音频", "radio" => "视频");
    private $news = "article";
    private $pics = "pics";
    private $music = "audio";
    private $vedio = "radio";
    private $send = "发布";
    private $limit = "20";
    private $noGuanzhi = "暂无观止频道数据";
    private $replaceRPath = '/qiguan/discover/';


    /***
     * @name=>添加图文、更新图文操作
     * @
     ****/
    public function addArticle()
    {
        $session = session("qg_auth");
        $arr["user_id"] = $session[0]['user_id'];
        $opinion_method = I("opinion_method");
        $arr["tags"] = array_filter(I("tags"));


        $arr["title"] = trim(I("title"));
        $arr["status"] = trim(I("send_status"));
        $arr["msg_abstract"] = htmlspecialchars_decode(I("summary"));
        $thumbnail = array_filter(I("thumbnail"));
        $arr["send"] = trim(I("send"));
        $arr["channel"] = trim(I("channel"));
        $arr["media_type"] = $this->news;
        $arr["publish_time"] = trim(I("publish_time"));
        if ($arr["publish_time"] == 0) {
            $arr["publish_time"] = date("Y-m-d H:i:s");
        }

        if (!empty($thumbnail)) {
            $arr["thumbnail_url"] = implode("||", $thumbnail);
        }
        $arr["media_time"] = $arr["publish_time"];
        $arr["summary"] = $arr["msg_abstract"];
        //content
        $content = htmlspecialchars_decode(I("content"));
        preg_match_all("<img.*?src=\"(.*?.*?)\".*?>", $content, $match);
        foreach ($match[1] as $val) {
            $imgsrc[] = basename($val);
        }
        $content = str_replace($this->replaceRPath, C("replaceYPath"), $content);
        $arr["content"] = $content;
        $arr["guanzhi_id"] = trim(I("guanzhi_id"));


        //rowkey

        $StreamInfoModel = new StreamInfoModel();
        if ($opinion_method != "update") {
            $arr["layout"] = $this->send;
            $arr["rowkey"] = getKey($arr["title"] . $arr["publish_time"] . $arr["msg_id"]);
            $arr["details_url"] = $arr["rowkey"] . ".html";

            /***********insert area***********/
            $msgID = $StreamInfoModel->addStreamAction($arr);
            $arr["msg_id"] = $msgID;
            //mediaDetail data
            $StreamInfoModel->addMediaDetail($arr);
            //StreamMedia data
            $this->addStreamMedia($arr);
            //GuanzhiMsg data

            if ($arr["guanzhi_id"] != $this->noGuanzhi) {
                $this->addGuanzhiMsg($arr);
            }
            //tag
            $this->addTagsMedia($arr, $msgID);

        } else {
            /***********update area***********/

            $map["msg_id"] = I("msg_id");

            $result = $StreamInfoModel->getStreamInfo($map);
            $updateID = $StreamInfoModel->getStreamMedia($map);
            $GuanZhiID = $StreamInfoModel->getGuanzhiMsg($map);


            if ($result[0]["content"] != $arr["content"] ||
                $result[0]["title"] != $arr["title"] ||
                $result[0]["msg_abstract"] != $arr["msg_abstract"] ||
                $result[0]["channel"] != $arr["channel"] ||
                $result[0]["publish_time"] != $arr["publish_time"] ||
                $result[0]["status"] != $arr["status"] ||
                $result[0]["thumbnail_url"] != $arr["thumbnail_url"]
            ) {

                $StreamInfoModel->updateStreamInfo($map, $arr);

                $wmap["rowkey"] = strReplace($updateID[0]['final_content']);
                $StreamInfoModel->updateMediaDetail($wmap, $arr);
            }

            if ($result[0]["thumbnail_url"] != $arr["thumbnail_url"]) {
                $arr["overview_pic"] = $arr["thumbnail_url"];
                $StreamInfoModel->updateStreamMedia($map, $arr);
            }

            if ($GuanZhiID[0]["guanzhi_id"] != $arr["guanzhi_id"]) {
                $guanArr["guanzhi_id"] = $arr["guanzhi_id"];
                $StreamInfoModel->updateGuanzhiMsg($map, $guanArr);
            }

            $this->checkTagsMedisStatus($arr, $map["msg_id"]);
        }

    }


    public function addPics()
    {
        $session = session("qg_auth");
        $arr["user_id"] = $session[0]['user_id'];
        $arr["tags"] = array_filter(I("tags"));
        $arr["title"] = trim(I("title"));
        $arr["msg_abstract"] = htmlspecialchars_decode(I("summary"));

        $upload_pic = array_filter(I('upload_pic'));
        $picdesc = array_filter(I('picdesc'));
        $checkbox = array_filter(I('checkbox'));
        $flip_check = array_flip($checkbox);
        $arr["status"] = trim(I("send_status"));


        foreach ($upload_pic as $key => $val_pic) {
            $sort_key = array_key_exists($key + 1, $flip_check);
            if ($sort_key) {
                $news_pics[] = $val_pic;
                $news_pics_desc[] = $picdesc[$key];
            } else {
                $no_thumail_pics[] = $val_pic;
                $no_thumail_pics_desc[] = $picdesc[$key];
            }
        }
        foreach ($no_thumail_pics as $list) {
            array_push($news_pics, $list);
        }

        foreach ($no_thumail_pics_desc as $list_desc) {
            array_push($news_pics_desc, $list_desc);
        }


        $arr["send"] = trim(I("send"));
        $arr["publish_time"] = trim(I("publish_time"));
        $arr["guanzhi_id"] = trim(I("guanzhi_id"));
        if ($arr["publish_time"] == 0) {
            $arr["publish_time"] = date("Y-m-d H:i:s");
        }
        $arr["channel"] = trim(I("channel"));
        $arr["media_type"] = $this->pics;


        $arr["layout"] = $this->send;
        $arr["media_time"] = $arr["publish_time"];


        $StreamInfoModel = new StreamInfoModel();
        $opinion_method = I("opinion_method");
        if ($opinion_method == "update") {
            /***********update area***********/
            $map["msg_id"] = I("msg_id");
            $StreamInfoModel->deletedAction($map);
            if (empty($news_pics)) {
                $news_pics = $upload_pic;
            }
        }

        $msgID = $StreamInfoModel->addStreamAction($arr);
        $arr["msg_id"] = $msgID;
        //MediaDetail
        foreach ($news_pics as $key => $val) {
            $arr["rowkey"] = getKey($arr["title"] . $arr["publish_time"] . $arr["msg_id"]);
            $arr["details_url"] = $arr["rowkey"] . ".html";
            $mediaDetailThumName = $val;
            $mediaDetailDesc = $news_pics_desc[$key];
            $arr["summary"] = $mediaDetailDesc;
            $arr["content"] = $mediaDetailThumName;
            $arr["thumbnail_url"] = $mediaDetailThumName;
            $StreamInfoModel->addMediaDetail($arr);
            //StreamMedia
            $this->addStreamMedia($arr);

        }
        //GuanzhiMsg
        if ($arr["guanzhi_id"] != $this->noGuanzhi) {
            $this->addGuanzhiMsg($arr);
        }
        //tag
        $this->addTagsMedia($arr, $msgID);
    }


    public function addVideo()
    {
        $session = session("qg_auth");
        $arr["user_id"] = $session[0]['user_id'];
        $opinion_method = I("opinion_method");
        $arr["tags"] = array_filter(I("tags"));
        $arr["status"] = trim(I("send_status"));
        $arr["title"] = trim(I("title"));
        $arr["type"] = trim(I("sendtype"));
        $arr["layout"] = I("audio_length");
        $arr["duration"] = I("audio_length");


        $arr["msg_abstract"] = htmlspecialchars_decode(I("summary"));
        $arr["send"] = trim(I("send"));
        $arr["publish_time"] = trim(I("publish_time"));
        if ($arr["publish_time"] == 0) {
            $arr["publish_time"] = date("Y-m-d H:i:s");
        }
        $arr["channel"] = trim(I("channel"));
        $arr["thumnailChannel"] = trim(I("thumnailChannel"));


        $thumbnail = array_filter(I('thumbnail'));
        $arr["content"] = I('upload_music');


        if (!empty($thumbnail[$arr["thumnailChannel"] - 2])) {

            $arr["thumbnail_url"] = $thumbnail[$arr["thumnailChannel"] - 2];
            if ($arr["type"] == $this->vedio) {
                $arr["media_type"] = $this->vedio;
            } else {
                $arr["media_type"] = $this->music;
            }

        }


        $arr["media_time"] = $arr["publish_time"];
        $arr["summary"] = $arr["msg_abstract"];
        $arr["guanzhi_id"] = trim(I("guanzhi_id"));



        $StreamInfoModel = new StreamInfoModel();
        if ($opinion_method != "update") {
            /***********insert area***********/
            $arr["rowkey"] = getKey($arr["title"] . $arr["publish_time"] . $arr["msg_id"]);
            $arr["details_url"] = $arr["rowkey"] . ".html";


            //StreamInfor
            $msgID = $StreamInfoModel->addStreamAction($arr);
            $arr["msg_id"] = $msgID;
            //mediaDetail
            $StreamInfoModel->addMediaDetail($arr);
            //StreamMedia
            $this->addStreamMedia($arr);
            //GuanzhiMsg

            if ($arr["guanzhi_id"] != $this->noGuanzhi) {
                $this->addGuanzhiMsg($arr);
            }
            //tag
            $this->addTagsMedia($arr, $msgID);
        } else {
            /***********update area***********/


            $map["msg_id"] = I("msg_id");
            $result = $StreamInfoModel->getStreamInfo($map);
            $updateID = $StreamInfoModel->getStreamMedia($map);
            $GuanZhiID = $StreamInfoModel->getGuanzhiMsg($map);

            if ($result[0]["content"] != $arr["content"] ||
                $result[0]["title"] != $arr["title"] ||
                $result[0]["msg_abstract"] != $arr["msg_abstract"] ||
                $result[0]["channel"] != $arr["channel"] ||
                $result[0]["publish_time"] != $arr["publish_time"] ||
                $result[0]["thumbnail_url"] != $arr["thumbnail_url"]
            ) {

                $StreamInfoModel->updateStreamInfo($map, $arr);

                $wmap["rowkey"] = strReplace($updateID[0]['final_content']);
                $StreamInfoModel->updateMediaDetail($wmap, $arr);
            }
            if ($result[0]["thumbnail_url"] != $arr["thumbnail_url"] ||
                $updateID[0]["duration"] !=  $arr["duration"]
            ) {
                $arr["overview_pic"] = $arr["thumbnail_url"];
                $StreamInfoModel->updateStreamMedia($map, $arr);
            }

            if ($GuanZhiID[0]["guanzhi_id"] != $arr["guanzhi_id"]) {
                $guanArr["guanzhi_id"] = $arr["guanzhi_id"];
                $StreamInfoModel->updateGuanzhiMsg($map, $guanArr);
            }
            $this->checkTagsMedisStatus($arr, $map["msg_id"]);
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
            if(!empty($arr["duration"])){
                $map["duration"] = $arr["duration"];
            }

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
        $result=$StreamInfoModel-> getGuanzhiRank( $map["guanzhi_id"]);
        if(empty($result[0]["rank"]) && $result[0]["rank"]!=0){
            $rank=0;
        }else{
            $rank=$result[0]["rank"]+1;
        }
        $map["rank"]=$rank;
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
            $StreamInfoModel = new StreamInfoModel();
            $this->result = $StreamInfoModel->getGuzhiInfor($map);
        } else {
            $this->result = "";
        }


        $data = $this->fetch("News/guzhi_type");
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
     *@内容库管理隐藏操作
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


    /****
     *@检验Tags的重复值
     ****/

    public function checkTagsMedisStatus($arr, $msg_id)
    {
        $map["msg_id"] = $msg_id;
        $StreamInfoModel = new StreamInfoModel();
        $tasResult = $StreamInfoModel->getTagMedia($map);
        foreach ($tasResult as $val) {
            $tagArr[] = $val["tag"];
        }
        $oldTagStr = implode("||", $tagArr);
        $newTagStr = implode("||", $arr["tags"]);

        if ($oldTagStr != $newTagStr) {
            $StreamInfoModel->delTagMedia($map);
            $this->addTagsMedia($arr, $map["msg_id"]);
        }
    }

    /****
     *@添加文章关键词
     ****/

    public function addTagsMedia($arr, $msg_id)
    {
        $StreamInfoModel = new StreamInfoModel();
        foreach ($arr["tags"] as $val) {
            $map["msg_id"] = $msg_id;
            $map["type"] = $arr["media_type"];
            $map["tag"] = $val;
            $StreamInfoModel->addTagMedia($map);
        }
    }

    /****
     *@上传图片、音频、视频
     ****/

    public function uploadFile()
    {
        $thumbnail = array($_FILES['thumbnail']);
        $name = $thumbnail[0]['name'];

        $subname = '';
        if (!empty($name)) {
            $type = substr($name, strrpos($name, "."), strlen($name));
            $saveName = $this->getMD($name);
            $arr["thumbnail_url"] = $saveName . $type;
            $this->uploadMVI($thumbnail, $this->path, $subname, $saveName);
        }
        $path = '.' . $this->path . $subname . $saveName . $type;
        $this->ajaxReturn($path);
    }

}

?>