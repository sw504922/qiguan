<?php

namespace Home\Model;

use Think\Model;

class AnswerInfoModel extends Model
{


    public function getAnswerInfo($status, $offset, $limit = 20)
    {

        $model = M();
        $sql = 'select answer_info.praise,answer_info.comment,answer_info.pv,user_info.user_name,stream_info.* from (select * from jrqg.answer_info where status="' . $status . '" group by msg_id) as answer_info';
        $sql .= ' left join (select user_name,user_id  from jrqg.user_info) as user_info on answer_info.user_id=user_info.user_id';
        $sql .= ' left join (select * from jrqg.stream_info) as stream_info on answer_info.msg_id=stream_info.msg_id';
        $sql .= ' limit ' . $offset . ',' . $limit;

        $result = $model->query($sql);
        return $result;

    }

    public function getAnswerInfoCount($status)
    {
        $model = M();
        $sql = 'select count(*) as cnt from jrqg.answer_info where status="' . $status . '"  group by msg_id ';
        $result = $model->query($sql);
        return $result[0]["cnt"];
    }


    public function updateAnswerInfo($map, $arr)
    {
        $model = M("jrqg.answer_info");
        $result = $model->where($map)->save($arr);
        return $result;
    }
}