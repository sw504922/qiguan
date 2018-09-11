<?php

namespace Home\Model;

use Think\Model;

class UserAccountInfoModel extends Model
{

    private $default = "default";
    private $tradeDate = "入金时间";
    private $regDate = "注册时间";
    private $allChannelType = "全部渠道类型";
    private $allManager = "全部商务人员";
    private $allConversion = "全部转化人";
    private $allProcess = "服务进度";

    public function getUserOpenAndTrade($chtype, $chname, $startTime, $endTime, $parent, $manager, $process, $datesearch, $offset, $limit)
    {

        $model = new Model();
        $sql = ' select * from report.user_fund_account_desc where (phone_mobile is not null or phone_mobile!="")';

        if ($datesearch == $this->tradeDate) {
            $sql .= ' and trade_time >="' . $startTime . '" and trade_time<"' . $endTime . '"';
        } else if ($datesearch == $this->regDate) {
            $sql .= ' and register_time >="' . $startTime . '" and register_time<"' . $endTime . '"';
        } else {
            $sql .= ' and account_time >="' . $startTime . '" and account_time<"' . $endTime . '"';
        }
        if (!empty($chname))
            $sql .= ' and user_name like "%' . $chname . '%"';

        if ($chtype != $this->allChannelType && !empty($chtype))
            $sql .= ' and channel_type="' . $chtype . '"';

        if ($manager != $this->allManager && !empty($manager))
            $sql .= ' and manager="' . $manager . '"';

        if ($parent != $this->allConversion && !empty($parent))
            $sql .= '  and conversion_name = "' . $parent . '"';

        if ($process != $this->allProcess)
            $sql .= '  and process="' . $process . '"';

//        if ($datesearch ==$this->tradeDate) {
//            $sql .= " order by trade_time desc";
//        } else if ($datesearch ==$this->regDate) {
//            $sql .= " order by register_time desc";
//        } else {
//            $sql .= " order by account_time desc";
//        }
        $sql .= ' order by process_time asc';

        $sql .= ' limit ' . $offset . ',' . $limit;


        $result = $model->query($sql);
        return $result;
    }


    public function getUserOpenALLTrade($chtype, $chname, $startTime, $endTime, $parent, $manager, $process, $datesearch)
    {

        $model = new Model();
        $sql = ' select funds_account from report.user_fund_account_desc where (funds_account is not null or funds_account!="")';

        if ($datesearch == $this->tradeDate) {
            $sql .= ' and trade_time >="' . $startTime . '" and trade_time<"' . $endTime . '"';
        } else if ($datesearch == $this->regDate) {
            $sql .= ' and register_time >="' . $startTime . '" and register_time<"' . $endTime . '"';
        } else {
            $sql .= ' and account_time >="' . $startTime . '" and account_time<"' . $endTime . '"';
        }

        if (!empty($chname))
            $sql .= ' and user_name like "%' . $chname . '%"';

        if ($chtype != $this->allChannelType && !empty($chtype))
            $sql .= ' and channel_type="' . $chtype . '"';

        if ($manager != $this->allManager && !empty($manager))
            $sql .= ' and manager="' . $manager . '"';

        if ($parent != $this->allConversion && !empty($parent))
            $sql .= '  and conversion_name = "' . $parent . '"';

        if ($process != $this->allProcess)
            $sql .= '  and process="' . $process . '"';


        $result = $model->query($sql);
        return $result;
    }


    public function getUserOpenAndTradeCount($chtype, $chname, $startTime, $endTime, $parent, $manager, $process, $datesearch, $type)
    {
        $model = new Model();
        $sql = ' select count(*) cnt from report.user_fund_account_desc where (phone_mobile is not null or phone_mobile!="")';

        if ($datesearch == $this->tradeDate) {
            $sql .= ' and trade_time >="' . $startTime . '" and trade_time<"' . $endTime . '"';
        } else if ($datesearch == $this->regDate) {
            $sql .= ' and register_time >="' . $startTime . '" and register_time<"' . $endTime . '"';
        } else {
            $sql .= ' and account_time >="' . $startTime . '" and account_time<"' . $endTime . '"';
        }
        if ($type == "open")
            $sql .= ' and (funds_account is not null or funds_account!="") ';

        if (!empty($chname))
            $sql .= ' and user_name like "%' . $chname . '%"';

        if ($chtype != $this->allChannelType && !empty($chtype))
            $sql .= ' and channel_type="' . $chtype . '"';

        if ($manager != $this->allManager && !empty($manager))
            $sql .= ' and manager="' . $manager . '"';

        if ($parent != $this->allConversion && !empty($parent))
            $sql .= '  and conversion_name = "' . $parent . '"';

        if ($process != $this->allProcess)
            $sql .= '  and process="' . $process . '"';


        $result = $model->query($sql);
        return $result[0]["cnt"];
    }


    public function getNowAsset($fund_account)
    {
        $model = new Model();
        $sql = ' SELECT net_assets asset,info_date,account FROM miningtrade.trade_user_money where info_date=(select max(info_date) info_date from miningtrade.trade_user_money where adapter="Ayers1") and adapter="Ayers1"';
        $sql .= ' and account in("' . $fund_account . '")';

        $result = $model->db("1", "APP_DB_DATA")->query($sql);
        return $result;
    }

    public function getLastAsset($fund_account, $info_date)
    {
        $model = new Model();
        $sql = ' SELECT net_assets asset FROM miningtrade.trade_user_money where info_date="' . $info_date . '" and adapter="Ayers1"';
        $sql .= ' and account ="' . $fund_account . '"';

        $result = $model->db("1", "APP_DB_DATA")->query($sql);
        return $result[0]['asset'];
    }


    public function getFirstAsset($fund_account)
    {
        $model = new Model();
        $sql = ' select a.* from ';
        $sql .= ' (SELECT min(info_date) as info_date, account,net_assets as asset FROM miningtrade.trade_user_money where  adapter="Ayers1"  and net_assets!=0 ';
        $sql .= ' and account in("' . $fund_account . '") GROUP BY account,asset ORDER BY account asc) as a INNER JOIN ';
        $sql .= '  (SELECT min(info_date) as info_date, account FROM miningtrade.trade_user_money where  adapter="Ayers1"  and net_assets!=0 ';
        $sql .= ' and account in("' . $fund_account . '")';
        $sql .= ' GROUP BY account ORDER BY account asc) as b on a.info_date=b.info_date and a.account=b.account';
        $result = $model->db("1", "APP_DB_DATA")->query($sql);
        return $result;
    }


    public function getFirstTransfer($fund_account)
    {
        $model = new Model();
        $sql = ' select a.* from ';
        $sql .= ' (SELECT min(info_date) as info_date, account,asset*ex as assets  FROM miningtrade.trade_stock_transfer where  adapter="Ayers1"  and asset>0 ';
        $sql .= ' and account in("' . $fund_account . '") GROUP BY account,assets ORDER BY account asc) as a INNER JOIN ';
        $sql .= '  (SELECT min(info_date) as info_date, account FROM miningtrade.trade_stock_transfer where  adapter="Ayers1"  and asset>0 ';
        $sql .= ' and account in("' . $fund_account . '")';
        $sql .= ' GROUP BY account ORDER BY account asc) as b on a.info_date=b.info_date and a.account=b.account';
        $result = $model->db("1", "APP_DB_DATA")->query($sql);
        return $result;
    }


    public function getUserFundAccountDesc($user_id)
    {
        $model = new Model();
        $sql = 'select * from report.user_fund_account_desc where user_id="' . $user_id . '"';
        $result = $model->query($sql);
        return $result;
    }

    public function addUserFundAccountDesc($arr)
    {
        $model = D("report.user_fund_account_desc");
        $model->add($arr);
    }

    public function updateUserFundAccountDesc($map, $arr)
    {
        $model = D("report.user_fund_account_desc");
        $result = $model->where($map)->save($arr);
        return $result;
    }


    public function getTradeFlowing($fund_account)
    {
        $model = new Model();
        $sql = ' SELECT * FROM miningtrade.trade_user_transfer where account="' . $fund_account . '" and adapter="Ayers1"';
        $result = $model->db("1", "APP_DB_DATA")->query($sql);
        return $result;
    }


    public function getResisterNum()
    {

    }


    public function getGlodDataCount($startTime, $endTime, $chtype, $chname, $manager, $parent, $process, $type)
    {
        $model = new Model();

        $sql = 'select ' . $type . ' cnt from (select account,asset,info_date as stat_time_day from miningtrade.trade_user_transfer where  asset<0 and adapter="Ayers1" and info_date>="' . $startTime . '" and info_date<="' . $endTime . '") as trade_transfer ';
        $sql .= 'inner join (select * from report.user_fund_account_desc) as user_fund on trade_transfer.account=user_fund.funds_account';
        $sql .= ' where (phone_mobile is not null or phone_mobile!="")';

        if (!empty($chname))
            $sql .= ' and user_name like "%' . $chname . '%"';

        if ($chtype != $this->allChannelType && !empty($chtype))
            $sql .= ' and channel_type="' . $chtype . '"';

        if ($manager != $this->allManager && !empty($manager))
            $sql .= ' and manager="' . $manager . '"';

        if ($parent != $this->allConversion && !empty($parent))
            $sql .= '  and conversion_name like "%' . $parent . '%"';

        if ($process != $this->allProcess)
            $sql .= '  and glod_process="' . $process . '"';

        $result = $model->query($sql);
        return $result[0]["cnt"];
    }


    public function getGlodData($startTime, $endTime, $chtype, $chname, $manager, $parent, $process, $offset, $limit)
    {

        $model = new Model();

        $sql = 'select * from (select account,asset,info_date as stat_time_day from miningtrade.trade_user_transfer where  asset<0 and adapter="Ayers1" and info_date>="' . $startTime . '" and info_date<="' . $endTime . '") as trade_transfer ';
        $sql .= 'inner join (select * from report.user_fund_account_desc) as user_fund on trade_transfer.account=user_fund.funds_account';
        $sql .= ' where (phone_mobile is not null or phone_mobile!="")';
        if (!empty($chname))
            $sql .= ' and user_name like "%' . $chname . '%"';

        if ($chtype != $this->allChannelType && !empty($chtype))
            $sql .= ' and channel_type="' . $chtype . '"';

        if ($manager != $this->allManager && !empty($manager))
            $sql .= ' and manager="' . $manager . '"';

        if ($parent != $this->allConversion && !empty($parent))
            $sql .= '  and conversion_name like "%' . $parent . '%"';

        if ($process != $this->allProcess)
            $sql .= '  and glod_process="' . $process . '"';

        $sql .= " order by glod_process_time desc";
        $sql .= ' limit ' . $offset . ',' . $limit;

        $result = $model->query($sql);
        return $result;
    }


    public function getAccountInfo($user_id)
    {
        $model = new Model();
        $sql = 'select * from report.user_account_info where user_id in("' . $user_id . '")';
        $result = $model->query($sql);
        return $result;
    }

    public function getUserGoldenInfo($user_id)
    {
        $model = new Model();
        $sql = 'select * from report.user_golden_info where user_id in("' . $user_id . '")';
        $result = $model->query($sql);
        return $result;

    }


    /***分割线***数据统计区域******/


    /******全部*********/
    public function getAllRegisterUser($start_time, $end_time)
    {
        $model = new Model();
        $sql = 'select count(distinct(user_id)) as cnt from report.user_register_change where user_type!=6 and user_type!="-6"';
        if (!empty($start_time) && !empty($end_time))
            $sql .= ' and stat_time_day>="' . $start_time . '" and stat_time_day<="' . $end_time . '" and log_type="reg"';
        $result = $model->query($sql);
        return $result[0]['cnt'];
    }

    public function getAllAccount($start_time, $end_time)
    {
        $model = new Model();
        $sql = "SELECT count(DISTINCT(funds_account)) as  cnt from user_account_info";
        if (!empty($start_time) && !empty($end_time))
            $sql .= ' where stat_time_day>="' . $start_time . '" and stat_time_day<="' . $end_time . '"';

        $result = $model->query($sql);
        return $result[0]['cnt'];
    }


    public function getAllTradeUser($start_time, $end_time)
    {
        $model = new Model();
        $sql = 'select count(DISTINCT funds_account) cnt from report.user_golden_info where type=0 and stat_time_day>="' . $start_time . '" and stat_time_day<"' . $end_time . '"';
        $result = $model->query($sql);
        return $result[0]['cnt'];
    }

    public function getAllMoney($start_time, $end_time, $status)
    {
        $model = new Model();
        $sql = 'select sum(transfer_amount)  cnt from  miningaccount.deposit_detail where audit_status="300010" and audit_date>="' . $start_time . '" and audit_date<"' . $end_time . '"';
        if ($status != $this->default) {
            $sql .= ' and funds_account in("' . $status . '")';
        }

        $result = $model->db("1", "APP_DB_DATA")->query($sql);
        return $result[0]['cnt'];
    }

    public function getAllChannel($start_time, $end_time)
    {
        $model = new Model();
        $sql = 'select count(id) as cnt from channel_ananlysis_name where create_time >="' . $start_time . '" and create_time<"' . $end_time . '"';
        $result = $model->query($sql);
        return $result[0]['cnt'];
    }


    //查询funds_account
    public function getFundAccountMoney($chtype, $startTime, $endTime, $manager, $coversion)
    {
        $model = new Model();
        $sql = 'SELECT funds_account FROM `user_fund_account_desc`';
        $sql .= ' where (funds_account!="" or funds_account is not null) and register_time>="' . $startTime . '" and register_time<"' . $endTime . '"';

        if ($chtype != $this->allChannelType && !empty($chtype))
            $sql .= ' and channel_type="' . $chtype . '"';

        if ($manager != $this->allManager && !empty($manager))
            $sql .= ' and manager="' . $manager . '"';

        if ($coversion != $this->allConversion && !empty($coversion))
            $sql .= '  and conversion_name = "' . $coversion . '"';
        $result = $model->query($sql);
        return $result;
    }


    public function getChannelGroupAll($startTime, $endTime, $offset, $limit)
    {
        $model = new Model();
        $sql = 'select * from (select * from (select * from (select * from ( ';
        $sql .= 'select count(distinct(user_id)) as register,channel_source from report.user_register_change where user_type!=6 and user_type!="-6" and stat_time_day>="' . $startTime . '" and stat_time_day<="' . $endTime . '" and log_type="reg" GROUP BY channel_source) as a
inner JOIN (select channel,channel_name,channel_type from channel_ananlysis_name) as b on a.channel_source=b.channel) as c 
left join (select count(funds_account) as account,channel as account_channel from user_account_info where stat_time_day>="' . $startTime . '" and stat_time_day<="' . $endTime . '" group by channel) as d on c.channel=d.account_channel) as e 
left join(select count(funds_account) as trade,channel_register from user_golden_info where stat_time_day>="' . $startTime . '" and stat_time_day<="' . $endTime . '" group by channel_register) as f on e.channel=f.channel_register) as g 
left join (select conversion_name,manager,ch_code from channel_info) as h on h.ch_code=g.channel';
        $sql .= ' limit ' . $offset . ',' . $limit;
        $result = $model->query($sql);
        return $result;
    }

    public function getChannelGroupCountAll($startTime, $endTime)
    {
        $model = new Model();
        $sql = 'select count(*) as cnt from (select * from (select * from (select * from ( ';
        $sql .= 'select count(distinct(user_id)) as register,channel_source from report.user_register_change where user_type!=6 and user_type!="-6" and stat_time_day>="' . $startTime . '" and stat_time_day<="' . $endTime . '" and log_type="reg" GROUP BY channel_source) as a
inner JOIN (select channel,channel_name,channel_type from channel_ananlysis_name) as b on a.channel_source=b.channel) as c 
left join (select count(funds_account) as account,channel as account_channel from user_account_info where stat_time_day>="' . $startTime . '" and stat_time_day<="' . $endTime . '" group by channel) as d on c.channel=d.account_channel) as e 
left join(select count(funds_account) as trade,channel_register from user_golden_info where stat_time_day>="' . $startTime . '" and stat_time_day<="' . $endTime . '" group by channel_register) as f on e.channel=f.channel_register) as g 
left join (select conversion_name,manager,ch_code from channel_info) as h on h.ch_code=g.channel';
        $result = $model->query($sql);
        return $result[0]["cnt"];
    }

    /****筛选条件区域******/
    public function getFundTableAllSum($chtype, $startTime, $endTime, $manager, $coversion)
    {
        $model = new Model();
        $sql = 'SELECT count(user_id) as register_sum, count(funds_account) as account_sum ,count(Distinct channel) as channel_sum ,count(trade_time) as trade_sum FROM `user_fund_account_desc`';

        $sql .= ' where ((register_time>="' . $startTime . '" and register_time<"' . $endTime . '") or (account_time>="' . $startTime . '" and account_time<"' . $endTime . '") or (trade_time>="' . $startTime . '" and trade_time<"' . $endTime . '"))  ';

        if ($chtype != $this->allChannelType && !empty($chtype))
            $sql .= ' and channel_type="' . $chtype . '"';

        if ($manager != $this->allManager && !empty($manager))
            $sql .= ' and manager="' . $manager . '"';

        if ($coversion != $this->allConversion && !empty($coversion))
            $sql .= '  and conversion_name = "' . $coversion . '"';

        $result = $model->query($sql);
        return $result[0];
    }

    public function getChannelGroup($chtype, $startTime, $endTime, $manager, $coversion, $offset, $limit)
    {
        $model = new Model();
        $sql = 'select * from ( ';
        $sql .= 'SELECT manager,count(user_id) as register,count(funds_account) account ,count(trade_time) trade,channel_type,channel_name,channel FROM `user_fund_account_desc` ';
        $sql .= ' where ((register_time>="' . $startTime . '" and register_time<"' . $endTime . '") or (account_time>="' . $startTime . '" and account_time<"' . $endTime . '") or (trade_time>="' . $startTime . '" and trade_time<"' . $endTime . '"))  ';
        if ($chtype != $this->allChannelType && !empty($chtype))
            $sql .= ' and channel_type="' . $chtype . '"';

        if ($manager != $this->allManager && !empty($manager))
            $sql .= ' and manager="' . $manager . '"';

        if ($coversion != $this->allConversion && !empty($coversion))
            $sql .= '  and conversion_name = "' . $coversion . '"';
        $sql .= ' group by channel_name) as a left join';
        $sql .= ' (select conversion_name,ch_code from channel_info) as b ';
        $sql .= ' on a.channel=b.ch_code';
        $sql .= ' limit ' . $offset . ',' . $limit;

        $result = $model->query($sql);
        return $result;
    }

    public function getChannelGroupCount($chtype, $startTime, $endTime, $manager, $coversion, $type)
    {
        $model = new Model();
        $sql = ' select count(*) as cnt from (';
        $sql .= ' SELECT channel,channel_name,count(' . $type . ') as cnt FROM `user_fund_account_desc` ';
        $sql .= ' where ((register_time>="' . $startTime . '" and register_time<"' . $endTime . '") or (account_time>="' . $startTime . '" and account_time<"' . $endTime . '") or (trade_time>="' . $startTime . '" and trade_time<"' . $endTime . '"))  ';
        if ($chtype != $this->allChannelType && !empty($chtype))
            $sql .= ' and channel_type="' . $chtype . '"';

        if ($manager != $this->allManager && !empty($manager))
            $sql .= ' and manager="' . $manager . '"';

        if ($coversion != $this->allConversion && !empty($coversion))
            $sql .= '  and conversion_name = "' . $coversion . '"';
        $sql .= ' group by channel_name';
        $sql .= ') as a';
        $result = $model->query($sql);
        return $result;
    }


    public function getPieData($chtype, $startTime, $endTime, $manager, $coversion, $type)
    {
        $model = new Model();
        $sql = 'SELECT channel_type,count(' . $type . ') as cnt FROM `user_fund_account_desc` ';
        $sql .= ' where ((register_time>="' . $startTime . '" and register_time<"' . $endTime . '") or (account_time>="' . $startTime . '" and account_time<"' . $endTime . '") or (trade_time>="' . $startTime . '" and trade_time<"' . $endTime . '"))  ';
        if ($chtype != $this->allChannelType && !empty($chtype))
            $sql .= ' and channel_type="' . $chtype . '"';

        if ($manager != $this->allManager && !empty($manager))
            $sql .= ' and manager="' . $manager . '"';

        if ($coversion != $this->allConversion && !empty($coversion))
            $sql .= '  and conversion_name = "' . $coversion . '"';


        $sql .= ' and (' . $type . ' is not null or ' . $type . '!="")';
        $sql .= ' group by channel_type';
        $result = $model->query($sql);

        return $result;
    }


    public function getRGTFundAccount($chtype, $startTime, $endTime, $manager, $coversion)
    {
        $model = new Model();
        $sql = ' SELECT user_id,conversion_name,manager,funds_account,channel,user_name,channel_name,channel_type FROM `user_fund_account_desc`';
        $sql .= ' where ((register_time>="' . $startTime . '" and register_time<"' . $endTime . '") or (account_time>="' . $startTime . '" and account_time<"' . $endTime . '") or (trade_time>="' . $startTime . '" and trade_time<"' . $endTime . '"))  ';
        $sql .= ' and (trade_time!="" or trade_time is not null) ';
        if ($chtype != $this->allChannelType && !empty($chtype))
            $sql .= ' and channel_type="' . $chtype . '"';

        if ($manager != $this->allManager && !empty($manager))
            $sql .= ' and manager="' . $manager . '"';

        if ($coversion != $this->allConversion && !empty($coversion))
            $sql .= '  and conversion_name = "' . $coversion . '"';
        $result = $model->query($sql);
        return $result;
    }


    public function getChannelMoney($funds_account)
    {
        $model = new Model();
        $sql = 'select sum(transfer_amount)  cnt from  miningaccount.deposit_detail where audit_status="300010"';
        $sql .= ' and funds_account in("' . $funds_account . '")';

        $result = $model->db("1", "APP_DB_DATA")->query($sql);

        return $result[0]['cnt'];
    }


    public function getConversionGroup($startTime, $endTime, $manager, $coversion, $type, $offset, $limit)
    {
        $model = new Model();
        $sql = 'SELECT ' . $type . ',count(user_id) as register, count(funds_account) as account ,count(Distinct channel) as channel ,count(trade_time) as trade FROM `user_fund_account_desc` ';
        $sql .= ' where ((register_time>="' . $startTime . '" and register_time<"' . $endTime . '") or (account_time>="' . $startTime . '" and account_time<"' . $endTime . '") or (trade_time>="' . $startTime . '" and trade_time<"' . $endTime . '"))  ';

        if ($manager != $this->allManager && !empty($manager))
            $sql .= ' and manager="' . $manager . '"';

        if ($coversion != $this->allConversion && !empty($coversion))
            $sql .= '  and conversion_name = "' . $coversion . '"';

        $sql .= ' group by ' . $type;
        if (!empty($offset)) {
            $sql .= ' limit ' . $offset . ',' . $limit;
        }

        $result = $model->query($sql);
        return $result;
    }


    public function getMonethData($startTime, $endTime, $manager, $coversion, $searchName, $type)
    {
        $model = new Model();
        if ($type == "account") {
            $column = "account_time";
        } else if ($type == "trade") {
            $column = "trade_time";
        }

        $sql = 'SELECT count(*) as cnt ,DATE_FORMAT(' . $column . ',"%Y-%m") as time from user_fund_account_desc ';
        $sql .= ' where ' . $column . '>="' . $startTime . '" and ' . $column . '<="' . $endTime . '"';

        if (!empty($manager))
            $sql .= ' and manager="' . $searchName . '"';

        if (!empty($coversion))
            $sql .= '  and conversion_name = "' . $searchName . '"';

        $sql .= " group by time";

        $result = $model->query($sql);
        return $result;
    }
}