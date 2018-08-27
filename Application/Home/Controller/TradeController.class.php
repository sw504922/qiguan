<?php
/**
 * Created by date 2018/8/23.
 * Author: wei.sun
 * Type:
 ***/

namespace Home\Controller;


use Home\Model\UserAccountInfoModel;
use Think\Controller;
use Home\Model\ChannelInfoModel;

class TradeController extends BaseController
{
    private $limit=50;

    /***
     * display Area
     ***/
    public function trade()
    {
        $this->display();
    }

    public function glod()
    {
        $this->display();
    }

    /***
     * data Area
     ****/

    public function getTradeData(){
        $session = session("od_auth");
        $TradeModel = new UserAccountInfoModel();
        $chtype = I("chtype");
        $chname = I("chname");
        $startTime = I("datestart");
        $endTime = I("dateend");
        $parent = I("parent");
        $manager = I("manager");
        $process = I("process");
        $datesearch = I("datesearch");
        if($session[0]["manager"]=="转化人员"){
            $process = $session[0]["name"];
        }

        if($session[0]["manager"]=="商务人员"){
            $manager = $session[0]["name"];
        }

        $new_page = I('new_page');
        if ($new_page == 0) {
            $new_page = 1;
        }
        $offset = ($new_page - 1) * $this->limit;


        $tradeData = $TradeModel->getUserOpenAndTrade($chtype, $chname, $startTime, $endTime, $parent, $manager,$process,$datesearch, $offset, $this->limit);


        $fund_account=array();
        foreach ($tradeData as $val){
            array_push($fund_account, $val["funds_account"]);

            $val["all_asset"]=0;
            $val["first_asset"]=0;
            $val["first_transfer"]=0;
            $tradeNewData[$val["funds_account"]]=$val;
        }

        $account = implode(",", $fund_account);
        $account = str_replace(",", "\",\"", $account);

        //总资产
        $nowAsset=$TradeModel->getNowAsset($account);
        foreach ($nowAsset as $val){
            $tradeNewData[$val["account"]]["all_asset"]=$val["asset"];
        }
        //第一次入金
        $firstAsset=$TradeModel-> getFirstAsset($account);
        foreach ($firstAsset as $val){
            $tradeNewData[$val["account"]]["first_asset"]=$val["asset"];
        }

        //第一次转仓
        $firstTransfer=$TradeModel->getFirstTransfer($account);
        foreach ($firstTransfer as $val){
            $tradeNewData[$val["account"]]["first_transfer"]=$val["assets"];
        }

        $this->result = $tradeNewData;
        $this->resultCount = $TradeModel->getUserOpenAndTradeCount($chtype, $chname, $startTime, $endTime, $parent, $manager,$process,$datesearch);
        $this->count = $offset + 1;
        $this->new_page = $new_page;

        $data = $this->fetch('Trade/get_trade');
        $this->ajaxReturn($data);
    }




    /***
     * @更新进度
     ****/
    public function updateProcess(){
        $TradeModel = new UserAccountInfoModel();
        $process = I("process");
        $fund_account = I("fund_account");

        $status=$TradeModel->getUserFundAccountDesc($fund_account);
        $arr["process"]=$process;
        if (empty($status)){
            $arr["funds_account"]=$fund_account;
           $status= $TradeModel->addUserFundAccountDesc($arr);
        }else{
            $map["funds_account"]=$fund_account;
            $status= $TradeModel->updateUserFundAccountDesc($map,$arr);
        }
        $this->ajaxReturn($status);
    }

    /***
     * @更新用户信息
     ****/
    public function updateWeChatAndDesc(){

        $TradeModel = new UserAccountInfoModel();
        $desc = I("desc");
        $wechat = I("wechat");
        $fund_account = I("fund_account");

        $arr["desc"]=$desc;
        $arr["wechat"]=$wechat;

        $status=$TradeModel->getUserFundAccountDesc($fund_account);
        if (empty($status)){
            $arr["funds_account"]=$fund_account;
            $status= $TradeModel->addUserFundAccountDesc($arr);
        }else{
            $map["funds_account"]=$fund_account;
            $status= $TradeModel->updateUserFundAccountDesc($map,$arr);
        }

        $this->ajaxReturn($status);
    }

    /***
     * @资金流水
     ****/

    public function getFlowerTrade(){
        $TradeModel = new UserAccountInfoModel();

        $fund_account = I("fund_account");
        $this->floweResult=$TradeModel->getTradeFlowing($fund_account);

        $data = $this->fetch('Trade/get_flower_water');
        $this->ajaxReturn($data);
    }









    public function getgetGlodData(){
        $session = session("od_auth");
        $TradeModel = new UserAccountInfoModel();
        $chtype = I("chtype");
        $chname = I("chname");
        $startTime = getdateYMD(I("datestart"));
        $endTime = getdateYMD(I("dateend"));
        $parent = I("parent");
        $manager = I("manager");
        $process = I("process");
        $datesearch = I("datesearch");
        if($session[0]["manager"]=="转化人员"){
            $process = $session[0]["name"];
        }

        if($session[0]["manager"]=="商务人员"){
            $manager = $session[0]["name"];
        }

        $new_page = I('new_page');
        if ($new_page == 0) {
            $new_page = 1;
        }
        $offset = ($new_page - 1) * $this->limit;


        $glodData = $TradeModel->getGlodData($startTime, $endTime, $offset, $this->limit);
        $fund_account=array();
        foreach ($glodData as $val){
            array_push($fund_account, $val["funds_account"]);
            $val["all_asset"]=0;

            $val["net_asset"]=abs( $val["asset"]*$val["ex"]);
            $glodNewData[$val["funds_account"]]=$val;
        }
        $account = implode(",", $fund_account);
        $account = str_replace(",", "\",\"", $account);

        //总资产
        $nowAsset=$TradeModel->getNowAsset($account);
        foreach ($nowAsset as $val){
            $glodNewData[$val["account"]]["all_asset"]=$val["asset"];
        }


        $userInfor=$TradeModel->getUserInfor($account,$chtype,$manager,$parent,$process);
        foreach ($userInfor as $val){
            $glodNewData[$val["funds_account"]]["name"]=$val["name"];
            $glodNewData[$val["funds_account"]]["phone_number"]=$val["phone_number"];
            $glodNewData[$val["funds_account"]]["manager"]=$val["manager"];
            $glodNewData[$val["funds_account"]]["wechat"]=$val["wechat"];
            $glodNewData[$val["funds_account"]]["process"]=$val["process"];
            $glodNewData[$val["funds_account"]]["desc"]=$val["desc"];
            $glodNewData[$val["funds_account"]]["conversion_name"]=$val["conversion_name"];
            $glodNewData[$val["funds_account"]]["channel_name"]=$val["channel_name"];
            $glodNewData[$val["funds_account"]]["channel_type"]=$val["channel_type"];
        }


        $this->result = $glodNewData;
        $this->resultCount = $TradeModel->getGlodDataCount($startTime, $endTime);
        $this->count = $offset + 1;
        $this->new_page = $new_page;

        $data = $this->fetch('Trade/get_glod');
        $this->ajaxReturn($data);
    }
}