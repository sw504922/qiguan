<?php
namespace Home\Model;
use Think\Model;

class StreamInfoModel extends Model
{

    function getChannel($channel){
        $model=M();
        $sql='select * from (select * from jrqg.stream_info where channel="'.$channel.'") as stream_info';
        $sql.=' left join (select user_name,user_id  from jrqg.user_info) as user_info on stream_info.user_id=user_info.user_id';
        $result=$model->query($sql);
        return $result;
    }
}