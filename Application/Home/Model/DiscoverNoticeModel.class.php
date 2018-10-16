<?php

namespace Home\Model;

use Think\Model;

class DiscoverNoticeModel extends Model
{



    public function addDiscoverNotice($arr)
    {
        $model = M("jrqg.discover_notice");
        $id = $model->add($arr);
        return $id;
    }


    public function getGuanzhiChoiceTopic($map){
        $model = M("jrqg.guanzhi_choice_topic");
        $result = $model->where($map)->select();
        return $result;
    }

    public function updateGuanzhiChoiceTopic($map,$arr){
        $model = M("jrqg.guanzhi_choice_topic");
        $result = $model->where($map)->save($arr);
        return $result;
    }
}