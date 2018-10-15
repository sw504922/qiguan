<?php

namespace Home\Model;

use Think\Model;

class StreamInfoModel extends Model
{

    function getChannel($channel, $user_id, $offset, $limit = 20)
    {
        $model = M();
        $sql = 'select * from (select * from jrqg.stream_info where status="' . $channel . '" and user_id="' . $user_id . '") as stream_info';
        $sql .= ' left join (select user_name,user_id  from jrqg.user_info) as user_info on stream_info.user_id=user_info.user_id';
        $sql .= ' limit ' . $offset . ',' . $limit;
        $result = $model->query($sql);
        return $result;
    }

    function getChanneCount($channel, $user_id)
    {
        $model = M();
        $sql = 'select count(*) cnt from (select * from jrqg.stream_info where status="' . $channel . '"  and user_id="' . $user_id . '") as stream_info';
        $sql .= ' left join (select user_name,user_id  from jrqg.user_info) as user_info on stream_info.user_id=user_info.user_id';
        $result = $model->query($sql);
        return $result[0]["cnt"];
    }


    function deletedAction($map)
    {

        $model = M("jrqg.stream_info");
        $model->where($map)->delete();

        $streamModel = M("jrqg.stream_media");
        $streamModel->where($map)->delete();

        $gzModel = M("jrqg.guanzhi_msg");
        $gzModel->where($map)->delete();

    }


    public function updateAction($map, $arr)
    {
        $model = M("jrqg.stream_info");
        $result = $model->where($map)->save($arr);
        return $result;
    }

    public function addStreamAction($arr)
    {
        $id = $this->getMaxMSGID();
        $arr["msg_id"] = $id;
        $model = M("jrqg.stream_info");
        $model->add($arr);
        return $id;
    }

    public function getMediaDetail($str)
    {
        $map["rowkey"] = $str;
        $model = M("jrqg.media_detail");
        $id = $model->where($map)->select();
        return $id;
    }

    public function getMediaDetailAll($map)
    {
        $model = M("jrqg.media_detail");
        $id = $model->where($map)->select();
        return $id;
    }

    public function addMediaDetail($arr)
    {
        $model = M("jrqg.media_detail");
        $id = $model->add($arr);
        return $id;
    }


    public function addStreamMedia($arr)
    {

        $model = M("jrqg.stream_media");
        $model->add($arr);
    }

    private function getMaxMSGID()
    {
        $model = M("jrqg.stream_info");
        $sql = 'select max(msg_id) as max  from jrqg.stream_info ';
        $result = $model->query($sql);
        $id = $result[0]["max"] + 1;

        switch (strlen($id)) {
            case 1:
                $num = "00000";
                break;
            case 2:
                $num = "0000";
                break;
            case 3:
                $num = "000";
                break;
        }

        return $num . $id;
    }

    function getStreamInfo($map)
    {

        $model = M("jrqg.stream_info");
        $result = $model->where($map)->select();
        return $result;
    }

    public function getStreamMedia($map)
    {
        $model = M("jrqg.stream_media");
        $result = $model->where($map)->select();
        return $result;
    }



    public function updateStreamInfo($map, $arr)
    {
        $model = M("jrqg.stream_info");
        $model->where($map)->save($arr);

    }

    public function updateStreamMedia($map, $arr)
    {
        $model = M("jrqg.stream_media");
        $model->where($map)->save();

    }

    public function updateMediaDetail($map, $arr){
        $model = M("jrqg.media_detail");

        $model->where($map)->save($arr);

    }


    public function addGuzhiInfo($arr)
    {
        $model = M("jrqg.guanzhi_info");
        $id = $model->add($arr);
        return $id;
    }

    public function getGuzhiInfor($map)
    {

        $model = M("jrqg.guanzhi_info");
        $result = $model->where($map)->select();
        return $result;
    }

    function deletedGuzhiAction($map)
    {

        $model = M("jrqg.guanzhi_info");
        $result = $model->where($map)->delete();
        return $result;
    }

    public function addGuanzhiMsg($arr)
    {
        $model = M("jrqg.guanzhi_msg");
        $model->add($arr);
    }


    public function getGuanzhiMsg($map)
    {
        $model = M("jrqg.guanzhi_msg");
        $result = $model->where($map)->select();
        return $result;
    }
}