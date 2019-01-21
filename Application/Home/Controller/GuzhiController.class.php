<?php

namespace Home\Controller;

use Home\Model\ChannelInfoModel;
use Home\Model\StreamInfoModel;
use Think\Controller;

class GuzhiController extends BaseController
{

    /*****
     *精选
     *  $cacheKey:定义唯一缓存key值
     ****/
    public function jingxuan()
    {
        $StreamInfoModel = new StreamInfoModel();
        $map["guanzhi_type"] = "zt";
        $result = $StreamInfoModel->getGuzhiInfor($map);
        $this->assign("result", $result);
        $this->display();
    }


    /*****
     *游历
     *  $cacheKey:定义唯一缓存key值
     ****/
    public function youli()
    {
        $StreamInfoModel = new StreamInfoModel();
        $map["guanzhi_type"] = "yl";
        $result = $StreamInfoModel->getGuzhiInfor($map);
        $this->assign("result", $result);
        $this->display();
    }

    /*****
     *问道
     *  $cacheKey:定义唯一缓存key值
     ****/
    public function wendao()
    {
        $StreamInfoModel = new StreamInfoModel();
        $map["guanzhi_type"] = "wd";
        $result = $StreamInfoModel->getGuzhiInfor($map);
        $this->assign("result", $result);
        $this->display();
    }

    /*****
     *博览
     *  $cacheKey:定义唯一缓存key值
     ****/
    public function bolan()
    {
        $StreamInfoModel = new StreamInfoModel();
        $map["guanzhi_type"] = "bl";
        $result = $StreamInfoModel->getGuzhiInfor($map);
        $this->assign("result", $result);
        $this->display();
    }


    /***
     * @添加观止频道
     ****/
    public function addJXMethod()
    {
        $arr["title"] = trim(I("title"));
        //$arr["summary"] = htmlspecialchars(I("summary"));
        $arr["guanzhi_desc"] = htmlspecialchars_decode(I("desc"));
        $arr["thumnailChannel"] = trim(I("thumnailChannel"));
        $thumbnail = array_filter(I('thumbnail'));

        $session = session("qg_auth");
        $arr["user_id"] = $session[0]['user_id'];
        $arr["guanzhi_type"] = trim(I("channel"));

        $arr["publish_time"] = Date("Y-m-d H:i:s");

        if (!empty($thumbnail[$arr["thumnailChannel"]])) {
            $arr["pic_url"] = $thumbnail[$arr["thumnailChannel"]];
            $arr["final_content"] = $thumbnail[$arr["thumnailChannel"]];
        }

        $StreamInfoModel = new StreamInfoModel();
        $StreamInfoModel->addGuzhiInfo($arr);
    }


    public function deltedMethod()
    {
        $StreamInfoModel = new StreamInfoModel();
        $map["guanzhi_id"] = I("id");
        $result = $StreamInfoModel->getGuzhiInfor($map);
        if (!empty($result)) {
            $StreamInfoModel->deletedGuzhiAction($map);
        }
    }


    public function banner()
    {
        $StreamInfoModel = new StreamInfoModel();
        $result = $StreamInfoModel->getGuzhiInfor("");
        foreach ($result as $key => $val) {
            $map["guanzhi_id"] = $val["guanzhi_id"];
            $status = $StreamInfoModel->getGuanzhiChoiceTopic($map);
            $result[$key]["status"] = $status[0]["status"];
        }

        $this->assign("result", $result);
        $this->display();
    }

    public function setStatusBanner()
    {
        $arr["status"] = I("status");
        $rowky = I("id");
        $list = explode(",", $rowky);
        foreach ($list as $val) {
            $map["guanzhi_id"] = $val;
            $StreamInfoModel = new StreamInfoModel();
            $result = $StreamInfoModel->getGuanzhiChoiceTopic($map);

            if (empty($result) && $arr["status"] == 1) {
                $arr["guanzhi_id"] = $map["guanzhi_id"];
                $StreamInfoModel->addGuanzhiChoiceTopic($arr);
            } else {
                $StreamInfoModel->updateGuanzhiChoiceTopic($map, $arr);
            }
        }

    }



    private $limit=10;

    public function getList(){
        $guanzhi_id = I("msg_id");
        $StreamInfoModel = new StreamInfoModel();

        $new_page = I('new_page');

        if ($new_page == 0 || empty($new_page)) {
            $new_page = 1;
        }

        $offset = ($new_page - 1) * $this->limit;

        $result = $StreamInfoModel->getGuanzhiData($guanzhi_id,$offset,$this->limit);
        $resultcount = $StreamInfoModel->getGuanzhiDataCount($guanzhi_id);

        $this->result = $result;
        $this->newsurl = C("newsurl");
        $this->resultCount = $resultcount;
        $this->new_page = $new_page;
        $this->msg_id = $guanzhi_id;
        $this->viewCount = $this->limit;
        $data = $this->fetch("Guzhi/get_content");
        $this->ajaxReturn($data);
    }

    public function updateGuanzhi(){
        $map["guanzhi_id"] =I("guanzhi_id");
        $arr["title"]=trim(I("editTitle"));
        $arr["guanzhi_desc"]=trim(I("editDesc"));
        $arr["pic_url"]=trim(I("thumbnail"));
        $arr["final_content"]=trim(I("thumbnail"));
        $StreamInfoModel = new StreamInfoModel();
        $StreamInfoModel->saveGuanzhiInfo($map,$arr);
    }

}

?>