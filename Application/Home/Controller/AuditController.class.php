<?php

namespace Home\Controller;

use Home\Model\ChannelInfoModel;
use Home\Model\StreamInfoModel;
use Think\Controller;

class AuditController extends BaseController
{


    private $limit = 20;
    public $mediaType = array("article" => "文章", "pics" => "图集", "audio" => "音频", "radio" => "视频");

    public function getmsg()
    {
        $this->assign("mediaTyped", $this->mediaType);
        $this->display();
    }


    /*****
     *内容库管理获取内容
     ****/

    public function getContent()
    {
        $session = session("qg_auth");
        $arr["user_id"] = $session[0]['user_id'];
        $search = trim(I('search'));
        $media_type = I('media_type');
        $new_page = I('new_page');
        if ($new_page == 0) {
            $new_page = 1;
        }
        $offset = ($new_page - 1) * $this->limit;
        $StreamInfoModel = new StreamInfoModel();
        $dataResult = $StreamInfoModel->getChannelAll($search, $media_type, $offset, $this->limit);
        $resultCount = $StreamInfoModel->getChanneAllCount($search, $media_type);

        foreach ($dataResult as $val) {
            $val["ch_media_type"] = $this->mediaType[$val["media_type"]];
            $result[] = $val;
        }
        $this->result = $result;
        $this->newsurl = C("newsurl");
        $this->resultCount = $resultCount;
        $this->new_page = $new_page;
        $this->viewCount = $this->limit;
        $data = $this->fetch("Audit/get_information");
        $this->ajaxReturn($data);
    }


}

?>