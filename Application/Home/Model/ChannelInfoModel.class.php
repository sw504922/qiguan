<?php

namespace Home\Model;

use Think\Model;

class ChannelInfoModel extends Model
{
    public function getChannelInfo($chtype, $chname, $startTime, $endTime, $parent, $manager,$coversion, $offset, $limit)
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
            $sql.=' and conversion_name like"%'.$coversion.'%"';

        $sql .= ' ) as b on a.channel=b.ch_code ';
        if (!empty($chname) ) {
            $sql .= ' where a.channel_name like "%' . $chname . '%"';
        }
        $sql .= 'ORDER BY b.create_time desc limit ' . $offset . ',' . $limit . '';

        $result = $model->query($sql);
        return $result;
    }

    public function getChannelInfoCount($chtype, $chname, $startTime, $endTime, $parent, $manager,$coversion)
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
            $sql.=' and conversion_name="'.$coversion.'"';

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


    public function updateChannelInfo($map,$arr){
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
        $sql = 'SELECT * FROM `od_user` where manager="商务人员"';
        $result = $model->query($sql);
        return $result;
    }

    /**
     * return $result:返回商务人员的姓名
     ***/
    public function getConversion()
    {
        $model = new Model();
        $sql = 'SELECT * FROM `od_user` where manager="转化人员"';
        $result = $model->query($sql);
        return $result;
    }


    public function addChannelData($code,$channel_name,$channel_type){

        $model=D("channel_ananlysis_name");
        $arr["channel"]=strtoupper(trim($code));
        $arr["channel_name"]=$channel_name;
        $arr["channel_type"]=$channel_type;
        $map["channel"]= $arr["channel"];

        $getResult=$model->where($map)->select();
        if(empty($getResult)){
            $result=$model->add($arr);
        }

    }
}