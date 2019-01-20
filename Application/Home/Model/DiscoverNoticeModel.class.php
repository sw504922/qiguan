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


    public function getDiscoverNotice($map)
    {
        $model = M("jrqg.discover_notice");
        $result = $model->where($map)->select();
        return $result;
    }
    public function updateDiscoverNotice($map,$arr){
        $model = M("jrqg.discover_notice");
        $result = $model->where($map)->save($arr);
        return $result;
    }


    public function getDiscoverNoticeLoop($map){
        $model = M("jrqg.discover_notice_loop");
        $result = $model->where($map)->select();
        return $result;
    }

    public function addDiscoverNoticeLoop($arr)
    {
        $model = M("jrqg.discover_notice_loop");
        $id = $model->add($arr);
        return $id;
    }

    public function updateDiscoverNoticeLoop($map,$arr){
        $model = M("jrqg.discover_notice_loop");
        $result = $model->where($map)->save($arr);
        return $result;
    }


}