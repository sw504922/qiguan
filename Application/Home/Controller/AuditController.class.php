<?php

namespace Home\Controller;

use Home\Model\ChannelInfoModel;
use Home\Model\StreamInfoModel;
use Think\Controller;

class AuditController extends BaseController
{



    private $limit=20;
    private $mediaType = array("article" => "文章", "pics" => "图集", "audio" => "音频", "radio" => "视频");
    public function information()
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
        $arr["user_id"] = $session[0]['user_id'];
        $new_page = I('new_page');
        if ($new_page == 0) {
            $new_page = 1;
        }
        $offset = ($new_page - 1) * $this->limit;

        $StreamInfoModel = new StreamInfoModel();
        $dataResult = $StreamInfoModel->getChannelAll( $offset, $this->limit);

        foreach ($dataResult as $val) {
            $val["ch_media_type"] = $this->mediaType[$val["media_type"]];
            $result[] = $val;
        }
        $this->result = $result;
        $this->resultCount = $StreamInfoModel->getChanneAllCount();
        $this->new_page = $new_page;
        $this->viewCount = $this->limit;
        $data = $this->fetch("Audit/get_information");
        $this->ajaxReturn($data);
    }



}

?>