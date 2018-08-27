<?php

namespace Home\Model;

use Think\Model;

class UserAccountInfoModel extends Model
{
    public function getUserOpenAndTrade($chtype, $chname, $startTime, $endTime, $parent, $manager, $process,$datesearch, $offset, $limit)
    {
        $model = new Model();
        $sql = 'SELECT * FROM ( SELECT * FROM ( SELECT * FROM(SELECT * FROM ( SELECT * FROM ';
        $sql.=' ( SELECT  stat_time AS register_time, user_id AS register_user FROM report.user_register_change WHERE user_type != 6 AND user_type !=- 6 AND log_type = "reg" ';
        $sql.='and stat_time >= "' . $startTime . '" AND stat_time < "' . $endTime . '"';

        $sql.=' ) AS register LEFT JOIN ( SELECT `name`, channel as user_channel, funds_account, phone_number, user_id, stat_time FROM report.user_account_info  ';
        if (!empty($chname))
            $sql .= ' where name like "%' . $chname . '%"';

        $sql.=' ) AS account ON account.user_id = register.register_user ) AS register_account
LEFT JOIN ( SELECT stat_time_day as golde_time, user_id AS gold_user FROM report.user_golden_info ) AS golden ON register_account.user_id = golden.gold_user) as user_info
left join (select channel_type,channel_name,channel as channel from report.channel_ananlysis_name) as channel_ananlysis_name on channel_ananlysis_name.channel=user_info.user_channel) as user_channel_info
left join (select ch_code,manager from report.channel_info) as channel_info_table on channel_info_table.ch_code=user_channel_info.channel) as user_fund_all 
left join (select funds_account as funds_account_user,wechat,process,`desc`,conversion_name from user_fund_account_desc) as user_fund_account_desc on user_fund_account_desc.funds_account_user=user_fund_all.funds_account';

//        if ($datesearch=="入金时间"){
//            $sql.=' where golde_time >="'.$startTime.'" and golde_time<"'.$endTime.'"';
//        }else if ($datesearch=="注册时间") {
//            $sql.=' where register_time >="'.$startTime.'" and register_time<"'.$endTime.'"';
//        }else{
//            $sql.=' where stat_time >="'.$startTime.'" and stat_time<"'.$endTime.'"';
//        }
        $sql.=' where phone_number is not null or phone_number!=""';
        if ($chtype != "全部渠道类型" && !empty($chtype))
            $sql .= ' and channel_type="' . $chtype . '"';

        if ($manager != "全部商务人员" && !empty($manager))
            $sql .= ' and manager="' . $manager . '"';

        if ($parent != "全部转化人"  && !empty($parent))
            $sql.='  and conversion_name = "' . $parent . '"';

        if ($process != "全部" && !empty($process))
            $sql .= '  and process="' . $process . '"';

        if ($datesearch=="入金时间"){
            $sql.=" order by golde_time desc";
        }else if($datesearch=="注册时间"){
            $sql.=" order by register_time desc";
        }else{
            $sql.=" order by stat_time desc";
        }
        $sql .= ' limit ' . $offset . ',' . $limit;


        $result = $model->query($sql);
        return $result;
    }


    public function getUserOpenAndTradeCount($chtype, $chname, $startTime, $endTime, $parent, $manager, $process,$datesearch)
    {
        $model = new Model();
        $sql = 'SELECT count(*) cnt FROM ( SELECT * FROM ( SELECT * FROM(SELECT * FROM ( SELECT * FROM ';
        $sql.=' ( SELECT  stat_time AS register_time, user_id AS register_user FROM report.user_register_change WHERE user_type != 6 AND user_type !=- 6 AND log_type = "reg" and stat_time >= "' . $startTime . '" AND stat_time < "' . $endTime . '"';

        $sql.=' ) AS register LEFT JOIN ( SELECT `name`, channel as user_channel, funds_account, phone_number, user_id, stat_time FROM report.user_account_info  ';
        if (!empty($chname))
            $sql .= ' where name like "%' . $chname . '%"';

        $sql.=' ) AS account ON account.user_id = register.register_user ) AS register_account
LEFT JOIN ( SELECT stat_time_day as golde_time, user_id AS gold_user FROM report.user_golden_info ) AS golden ON register_account.user_id = golden.gold_user) as user_info
left join (select channel_type,channel_name,channel as channel from report.channel_ananlysis_name) as channel_ananlysis_name on channel_ananlysis_name.channel=user_info.user_channel) as user_channel_info
left join (select ch_code,manager from report.channel_info) as channel_info_table on channel_info_table.ch_code=user_channel_info.channel) as user_fund_all 
left join (select funds_account as funds_account_user,wechat,process,`desc`,conversion_name from user_fund_account_desc) as user_fund_account_desc on user_fund_account_desc.funds_account_user=user_fund_all.funds_account';

//        if ($datesearch=="入金时间"){
//            $sql.=' where golde_time >="'.$startTime.'" and golde_time<"'.$endTime.'"';
//        }else if ($datesearch=="注册时间") {
//            $sql.=' where register_time >="'.$startTime.'" and register_time<"'.$endTime.'"';
//        }else{
//            $sql.=' where stat_time >="'.$startTime.'" and stat_time<"'.$endTime.'"';
//        }
        $sql.=' where phone_number is not null or phone_number!=""';

        if ($chtype != "全部渠道类型" && !empty($chtype))
            $sql .= ' and channel_type="' . $chtype . '"';

        if ($manager != "全部商务人员" && !empty($manager))
            $sql .= ' and manager="' . $manager . '"';

        if ($parent != "全部转化人"  && !empty($parent))
            $sql.='  and conversion_name like "%' . $parent . '%"';

        if ($process != "全部" && !empty($process))
            $sql .= '  and process="' . $process . '"';
        $result = $model->query($sql);
        return $result[0]["cnt"];
    }



    public function getNowAsset($fund_account){
        $model = new Model();
        $sql=' SELECT net_assets asset,info_date,account FROM miningtrade.trade_user_money where info_date=(select max(info_date) info_date from miningtrade.trade_user_money where adapter="Ayers1") and adapter="Ayers1"';
        $sql.=' and account in("'.$fund_account.'")';

        $result = $model->db("1", "APP_DB_DATA")->query($sql);
        return $result;
    }


    public function getFirstAsset($fund_account){
        $model = new Model();
        $sql=' select a.* from ';
        $sql.=' (SELECT min(info_date) as info_date, account,net_assets as asset FROM miningtrade.trade_user_money where  adapter="Ayers1"  and net_assets!=0 ';
        $sql.=' and account in("'.$fund_account.'") GROUP BY account,asset ORDER BY account asc) as a INNER JOIN ';
        $sql.='  (SELECT min(info_date) as info_date, account FROM miningtrade.trade_user_money where  adapter="Ayers1"  and net_assets!=0 ';
        $sql.=' and account in("'.$fund_account.'")';
        $sql.=' GROUP BY account ORDER BY account asc) as b on a.info_date=b.info_date and a.account=b.account';
        $result = $model->db("1", "APP_DB_DATA")->query($sql);
        return $result;
    }



    public function getFirstTransfer($fund_account){
        $model= new Model();
        $sql=' select a.* from ';
        $sql.=' (SELECT min(info_date) as info_date, account,asset*ex as assets  FROM miningtrade.trade_stock_transfer where  adapter="Ayers1"  and asset>0 ';
        $sql.=' and account in("'.$fund_account.'") GROUP BY account,assets ORDER BY account asc) as a INNER JOIN ';
        $sql.='  (SELECT min(info_date) as info_date, account FROM miningtrade.trade_stock_transfer where  adapter="Ayers1"  and asset>0 ';
        $sql.=' and account in("'.$fund_account.'")';
        $sql.=' GROUP BY account ORDER BY account asc) as b on a.info_date=b.info_date and a.account=b.account';
        $result = $model->db("1", "APP_DB_DATA")->query($sql);
        return $result;
    }



    public function getUserFundAccountDesc($fund_account){
        $model = new Model();
        $sql='select * from report.user_fund_account_desc where funds_account="'.$fund_account.'"';
        $result = $model->query($sql);
        return $result;
    }

    public function addUserFundAccountDesc($arr){
        $model = D("report.user_fund_account_desc");
        $model->add($arr);
    }
    public function updateUserFundAccountDesc($map,$arr){
        $model = D("report.user_fund_account_desc");
        $result = $model->where($map)->save($arr);
        return $result;
    }



    public function getTradeFlowing($fund_account){
        $model = new Model();
        $sql=' SELECT * FROM miningtrade.trade_user_transfer where account="'.$fund_account.'" and adapter="Ayers1"';
        $result = $model->db("1", "APP_DB_DATA")->query($sql);
        return $result;
    }


    public function getResisterNum(){

    }




    public function getGlodData($startTime, $endTime, $offset, $limit){
        $model = new Model();
        $sql='SELECT account as funds_account,info_date,asset,ex   FROM miningtrade.trade_user_transfer where asset<0 and adapter="Ayers1" ';
        $sql.=' and info_date >= "' . $startTime . '" AND info_date < "' . $endTime . '"';
        $sql.=" order by info_date desc";
        $sql .= ' limit ' . $offset . ',' . $limit;

        $result = $model->db("1", "APP_DB_DATA")->query($sql);
        return $result;
    }


    public function getGlodDataCount($startTime, $endTime){
        $model = new Model();
        $sql='SELECT count(*) cnt FROM miningtrade.trade_user_transfer where asset<0 and adapter="Ayers1" ';
        $sql.=' and info_date >= "' . $startTime . '" AND info_date < "' . $endTime . '"';

        $result = $model->db("1", "APP_DB_DATA")->query($sql);
        return $result[0]["cnt"];
    }


    public function getUserInfor($fund_account,$chtype,$manager,$parent,$process){

        $model = new Model();
        $sql='select * from (select * from (select * from (SELECT `name`,funds_account,phone_number,channel FROM `user_account_info` where funds_account in("'.$fund_account.'")';


        $sql.=' ) as acount left join (select manager,ch_code from channel_info ) as channel_info on acount.channel=channel_info.ch_code) as two ';
        $sql.='left join (select channel_name,channel_type,channel as ananchannel from channel_ananlysis_name) as channel_ananlysis_name on two.channel=channel_ananlysis_name.ananchannel) as three ';
        $sql.='left join (select wechat,process,`desc`,conversion_name,funds_account as funds_account_desc from user_fund_account_desc ) as user_fund_account_desc on user_fund_account_desc.funds_account_desc=three.funds_account';


        if ($chtype != "全部渠道类型" && !empty($chtype))
            $sql .= ' and channel_type="' . $chtype . '"';

        if ($manager != "全部商务人员" && !empty($manager))
            $sql .= ' and manager="' . $manager . '"';

        if ($parent != "全部转化人"  && !empty($parent))
            $sql.='  and conversion_name like "%' . $parent . '%"';

        if ($process != "全部" && !empty($process))
            $sql .= '  and process="' . $process . '"';
        $result = $model->query($sql);
        return $result;
    }
}