<?php

namespace Home\Controller;

use Home\Model\ChannelInfoModel;
use Think\Controller;

class ChannelController extends BaseController
{

    private static $path = "./Application/Home/Uploads/";
    private static $path_subName = "odcrm/images/";


    /***
     * display Area
     ***/
    public function channel()
    {
        $this->display();
    }


    /***
     * data Area
     ****/
    /***
     * @getChannelData:获取渠道数据
     * getParent:推荐人姓名
     * getChannelInfo:渠道sql
     * getChannelInfoCount:渠道数量sql
     *
     ***/
    public function getChannelData()
    {
        $session = session("od_auth");

        $chtype = I("chtype");
        $chname = I("chname");
        $startTime = I("datestart");
        $endTime = I("dateend");
        $parent = I("parent");

        if($session[0]["manager"]=="转化人员"){
            $coversion = $session[0]["name"];
        }else{
            $coversion="";
        }

        if($session[0]["manager"]=="商务人员"){
            $manager = $session[0]["name"];
        }else{
            $manager = I("manager");
        }


        $new_page = I('new_page');
        if ($new_page == 0) {
            $new_page = 1;
        }
        $offset = ($new_page - 1) * 50;
        $getParent = $this->getParent();

        foreach ($getParent as $value) {
            $arr[$value["channel_id"]] = $value["full_name"];
        }


        $channelModel = new ChannelInfoModel();
        $channeData = $channelModel->getChannelInfo($chtype, $chname, $startTime, $endTime, $parent, $manager,$coversion, $offset, 50);

        foreach ($channeData as $val) {
            $arrkey = array_key_exists($val["parent_id"], $arr);
            if ($arrkey) {
                $val["parent_name"] = $arr[$val["parent_id"]];
            } else {
                $val["parent_name"] = "--";
            }

            $result[] = $val;
        }


        $this->result = $result;
        $this->resultCount = $channelModel->getChannelInfoCount($chtype, $chname, $startTime, $endTime, $parent, $manager,$coversion);
        $this->count = $offset + 1;
        $this->new_page = $new_page;

        $data = $this->fetch('Channel/get_channel');
        $this->ajaxReturn($data);

    }


    /**
     * addChannelMethod:新增渠道
     * @return:status 插入状态：返回数据ID
     *
     *****/
    public function addChannelMethod()
    {
        $channelModel = new ChannelInfoModel();
        $arr["full_name"] = I("full_name");
        $arr["contact"] = I("contact");
        $arr["id_number"] = I("id_number");
        $arr["phone_number"] = I("phone_number");
        $arr["recv_account_name"] = I("recv_account_name");
        $arr["recv_bank"] = I("recv_bank");
        $arr["recv_account_code"] = I("recv_account_code");
        $arr["manager"] = I("manager");
        $arr["channel_type"] = I("channel_type");
        $arr["short_name"] = I("short_name");
        $arr["ch_code"] = I("ch_code");
        $arr["payment_mode"] = I("payment_mode");
        $arr["reward_detail"] = I("reward_detail");
        $reward_plan = array($_FILES['reward_plan']);
        $arr["qr_link"] = I("qr_link");
        $qr_code = array($_FILES['qr_code']);


        //奖励方案
        if ($reward_plan[0]["name"] != "") {
            $this->getBannerImage($reward_plan, static::$path, static::$path_subName);
            $arr["reward_plan"] = $reward_plan[0]["name"];
        }
        //渠道二维码图片
        if ($qr_code[0]["name"] != "") {
            $this->getBannerImage($qr_code, static::$path, static::$path_subName);
            $arr["qr_code"] = $qr_code[0]["name"];
        }


        //推荐人
        $parent = htmlspecialchars(I("parent_id"));
        if (!empty($parent) || $parent != "--") {
            $parent_id = $channelModel->getParentId($parent);
            $arr["parent_id"] = $parent_id;
        }

        $session = session("od_auth");
        $arr["create_user"] = $session[0]["name"];

        $conversion= implode(',', I("conversion_bai"));
        if ( $conversion!=","){
            $arr["conversion_name"] = implode(',', I("conversion"));
            $arr["conversion"]=$conversion;
        }


        $channelModel->addChannelData($arr["ch_code"], $arr["full_name"], $arr["channel_type"]);
        $status =$channelModel->addChannelInfo($arr);
        $this->ajaxReturn($status);

    }

    /***
     * updateChannelMethod:渠道信息的更新
     * @return:status 更新状态 0：更新失败，1：更新成功
     ***/
    public function updateChannelMethod()
    {
        $channelModel = new ChannelInfoModel();
        $arr["full_name"] = I("full_name");
        $arr["contact"] = I("contact");
        $arr["id_number"] = I("id_number");
        $arr["phone_number"] = I("phone_number");
        $arr["recv_account_name"] = I("recv_account_name");
        $arr["recv_bank"] = I("recv_bank");
        $arr["recv_account_code"] = I("recv_account_code");
        $arr["manager"] = I("manager");
        $arr["channel_type"] = I("channel_type");
        $arr["short_name"] = I("short_name");
        $arr["reward_detail"] = I("reward_detail");
        $arr["payment_mode"] = I("payment_mode");
        $reward_plan = array($_FILES['reward_plan']);
        //奖励方案
        if ($reward_plan[0]["name"] != "") {
            $this->getBannerImage($reward_plan, static::$path, static::$path_subName);
            $arr["reward_plan"] = $reward_plan[0]["name"];
        }


        //推荐人
        $parent = htmlspecialchars(I("parent_id"));
        if (!empty($parent) || $parent != "--") {
            $parent_id = $channelModel->getParentId($parent);
            $arr["parent_id"] = $parent_id;
        }

        $map["ch_code"] = I("ch_code");
        $session = session("od_auth");
        $arr["create_user"] = $session[0]["name"];

        $conversion= implode(',', I("conversion_bai"));
        if ( $conversion!=","){
            $arr["conversion_name"] = implode(',', I("conversion"));
            $arr["conversion"]=$conversion;
        }

        $status = $channelModel->updateChannelInfo($map, $arr);

        $this->ajaxReturn($status);
    }

}

?>