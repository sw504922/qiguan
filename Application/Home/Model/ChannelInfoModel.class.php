<?php

namespace Home\Model;

use Think\Model;

class ChannelInfoModel extends Model
{
    public function getChannelInfo($chtype, $chname, $startTime, $endTime, $parent, $manager, $coversion, $offset, $limit)
    {
        $model = new Model();

        $sql = 'select * from ';
        $sql .= ' (select * from report.channel_ananlysis_name ';
        if ($chtype != "全部渠道类型" && !empty($chtype))
            $sql .= 'where channel_type="' . $chtype . '"';

        $sql .= ' )as a inner join (select * from report.channel_info where create_time>="' . $startTime . '" and create_time<"' . $endTime . '"';
        if ($parent != "全部推荐人" && !empty($parent))
            $sql .= 'and parent_id="' . $parent . '"';
        if ($manager != "全部商务人员" && !empty($manager))
            $sql .= 'and manager="' . $manager . '"';
        if (!empty($coversion))
            $sql .= ' and conversion_name like"%' . $coversion . '%"';

        $sql .= ' ) as b on a.channel=b.ch_code ';
        if (!empty($chname)) {
            $sql .= ' where a.channel_name like "%' . $chname . '%"';
        }
        $sql .= 'ORDER BY b.create_time desc limit ' . $offset . ',' . $limit . '';

        $result = $model->query($sql);
        return $result;
    }

    public function getChannelInfoCount($chtype, $chname, $startTime, $endTime, $parent, $manager, $coversion)
    {
        $model = new Model();

        $sql = 'select count(*) cnt from ';

        $sql .= ' (select * from report.channel_ananlysis_name ';
        if ($chtype != "全部渠道类型" && !empty($chtype))
            $sql .= 'where channel_type="' . $chtype . '"';

        $sql .= ' )as a inner join (select * from report.channel_info where create_time>="' . $startTime . '" and create_time<"' . $endTime . '"';
        if ($parent != "全部推荐人" && !empty($parent))
            $sql .= 'and parent_id="' . $parent . '"';
        if ($manager != "全部商务人员" && !empty($manager))
            $sql .= 'and manager="' . $manager . '"';
        if (!empty($coversion))
            $sql .= ' and conversion_name="' . $coversion . '"';

        $sql .= ' ) as b on a.channel=b.ch_code ';
        if (!empty($chname)) {
            $sql .= ' where a.channel_name like "%' . $chname . '%"';
        }
        $sql .= 'ORDER BY b.create_time desc';

        $result = $model->query($sql);
        return $result[0]["cnt"];
    }


    public function getParentId($channel)
    {
        $model = new Model();
        $sql = 'select channel_id from report.channel_info where full_name = "' . $channel . '"';
        $result = $model->query($sql);
        return $result[0]["channel_id"];
    }


    public function addChannelInfo($arr)
    {
        $model = M("channel_info");
        $reuslt = $model->add($arr);
        return $reuslt;
    }


    public function updateChannelInfo($map, $arr)
    {
        $model = M("channel_info");
        $reuslt = $model->where($map)->save($arr);
        return $reuslt;
    }

    public function getChannelType()
    {
        $model = new Model();
        $sql = 'select channel_type  from report.channel_ananlysis_name where channel_type is not NULL  GROUP by channel_type ORDER by channel_type desc';
        $result = $model->query($sql);
        return $result;
    }

    /**
     * return $result:返回推荐人的姓名
     ***/
    public function getParent()
    {
        $model = new Model();
        $sql = 'select  a.channel_id,a.full_name,a.short_name,a.ch_code from report.channel_info as a right join (select parent_id  from report.channel_info where parent_id is not NULL GROUP by parent_id) as b on a.channel_id=b.parent_id';
        $result = $model->query($sql);
        return $result;
    }

    /**
     * return $result:返回商务人员的姓名
     ***/
    public function getManger()
    {
        $model = new Model();
        $sql = 'select user.name  from (SELECT * FROM `auth_group_access` where group_id="17")  as access inner join (select * from user) as user on access.uid=user.id';
        $result = $model->query($sql);
        return $result;
    }

    /**
     * return $result:返回转化人的姓名
     ***/
    public function getConversion()
    {
        $model = new Model();
        $sql = 'select user.name  from (SELECT * FROM `auth_group_access` where group_id="18")  as access inner join (select * from user) as user on access.uid=user.id';
        $result = $model->query($sql);
        return $result;
    }

    /***
     * sms model
     ***/
    public function getSMSTemplet(){
        $model = new Model();
        $sql = 'SELECT * FROM `sms` ORDER by `id` asc';
        $result = $model->query($sql);
        return $result;
    }

    public function saveSMSCount($id){
        $model = D("sms");
        $map["id"]=$id;

        $getResult = $model->where($map)->select();
        $arr["sms_count"]=$getResult[0]["sms_count"]+1;
        $result = $model->where($map)->save($arr);
        return $result;
    }

    public function addChannelData($code, $channel_name, $channel_type)
    {

        $model = D("channel_ananlysis_name");
        $arr["channel"] = strtoupper(trim($code));
        $arr["channel_name"] = $channel_name;
        $arr["channel_type"] = $channel_type;
        $map["channel"] = $arr["channel"];

        $getResult = $model->where($map)->select();
        if (empty($getResult)) {
            $result = $model->add($arr);
        }
    }

    public function updateChannelData($code, $channel_name, $channel_type)
    {

        $model = D("channel_ananlysis_name");
        $arr["channel"] = strtoupper(trim($code));
        $arr["channel_name"] = $channel_name;
        $arr["channel_type"] = $channel_type;
        $map["channel"] = $arr["channel"];

        $getResult = $model->where($map)->select();
        if ($getResult[0]["channel"] != $arr["channel"] || $getResult[0]["channel_name"] != $arr["channel_name"] || $getResult[0]["channel_type"] != $arr["channel_type"]) {
            $result = $model->where($map)->save($arr);
        }
    }


    /****
     * @ctontab Area
     *
     *****/

    public function getCoversionData()
    {

        $model = new Model();
        $sql = 'select conversion_name,conversion,ch_code from report.channel_info  GROUP BY conversion_name,ch_code';
      //  $sql = 'select conversion_name,conversion,ch_code from report.channel_info  where ch_code="APPLESOTRE"';
        $result = $model->query($sql);
        return $result;
    }


    public function getTask($channel, $start_time, $end_time)
    {
        $model = new Model();
        $sql = 'select * from (select * from (select * from( ';
        $sql .= ' SELECT stat_time AS register_time, user_id AS register_user,channel_source as register_channel FROM report.user_register_change WHERE user_type != 6 AND user_type !=- 6 AND log_type = "reg" and stat_time >= "' . $start_time . '" AND stat_time < "' . $end_time . '") as report_register ';
        $sql .= ' left join (select user_id,mobile_phone_number,area_country_code,nickname from miningstock.`user`) as mining_regiser on report_register.register_user=mining_regiser.user_id) as registerall';
        $sql .= ' left join (select channel_type,channel_name,channel as channel from report.channel_ananlysis_name) as channel_ananlysis_name on channel_ananlysis_name.channel=registerall.register_channel) as channel_or_user';
        $sql .= ' left join  (select ch_code,manager from report.channel_info ) as channel_info on channel_info.ch_code=channel_or_user.register_channel where  ';
        if (empty($channel)){
            $sql.='( register_channel="" or register_channel is null)';
        }else{
            $sql.=' register_channel="' . $channel . '"';
        }
        $result = $model->query($sql);
        return $result;
    }


    public function addFundDesc($arr)
    {
        $model = M("user_fund_account_desc");
        $map["user_id"] = $arr["user_id"];
        $getResult = $model->where($map)->select();
        if ($getResult[0]["user_id"] != $arr["user_id"]) {
            $model->add($arr);
        }
    }

    public function saveFundDesc($arr)
    {
        $model = M("user_fund_account_desc");
        $map["user_id"] = $arr["user_id"];
        $getResult = $model->where($map)->select();
        if ($getResult[0]["user_name"] != $arr["name"] || $getResult[0]["funds_account"] != $arr["funds_account"] || $getResult[0]["account_time"] != $arr["account_time"] || $getResult[0]["trade_time"] != $arr["trade_time"]) {
            $model->where($map)->save($arr);
        }
    }
}