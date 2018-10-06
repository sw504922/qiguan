<?php
namespace Home\Model;
use Think\Model;

class StreamInfoModel extends Model
{

    function getChannel($channel){
        $model=M();
        $sql='select * from (select * from jrqg.stream_info where channel="'.$channel.'") as stream_info';
        $sql.=' left join (select user_name,user_id  from jrqg.user_info) as user_info on stream_info.user_id=user_info.user_id';
        $sql.=' limit 0,20';
        $result=$model->query($sql);
        return $result;
    }

    function getChanneCountl($channel){
        $model=M();
        $sql='select count(*) cnt from (select * from jrqg.stream_info where channel="'.$channel.'") as stream_info';
        $sql.=' left join (select user_name,user_id  from jrqg.user_info) as user_info on stream_info.user_id=user_info.user_id';
        $result=$model->query($sql);
        return $result[0]["cnt"];
    }

    function getStreamInfo($map){
        $model = M();
        $sql='select * from jrqg.stream_info where msg_id="'.$map["msg_id"].'"';
        $result=$model->query($sql);
        return $result;
    }

    function deletedAction($map){
        $model = M();
        $sql='delete  from jrqg.stream_info where msg_id="'.$map["msg_id"].'"';
        $result=$model->query($sql);
        return $result;
    }


    public function updateAction($map,$arr){
        $model = M("jrqg.stream_info");
        $result=$model->where($map)->save($arr);
        return $result;
    }

    public function addStreamAction($arr){
        $id=$this->getMaxMSGID();
        $arr["msg_id"]=$id;
        $model = M("jrqg.stream_info");
        $result=$model->add($arr);
        return $result;
    }

    private function getMaxMSGID(){
        $model = M("jrqg.stream_info");
        $sql='select max(msg_id) as max  from jrqg.stream_info ';
        $result=$model->query($sql);
        $id=$result[0]["max"]+1;

        switch(strlen($id)){
            case 1:$num="00000"; break;
            case 2:$num="0000"; break;
            case 3:$num="000"; break;
        }

        return $num.$id;
    }
}