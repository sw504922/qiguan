<?php

namespace Home\Model;

use Think\Model;

class StreamInfoModel extends Model
{


    function getChannel($channel, $user_id, $media_type, $offset, $limit = 20)
    {
        $model = M();
        if ($media_type != "pics") {
            $sql = 'select a.*,b.final_content from jrqg.stream_info as a left join stream_media as b on a.msg_id=b.msg_id';
        } else {
            $sql = 'select a.*from jrqg.stream_info as a ';
        }

        $sql .= ' where a.status="' . $channel . '"';
        $sql .= ' and a.user_id="' . $user_id . '"';
        $sql .= ' and a.media_type="' . $media_type . '"';
        $sql .= ' ORDER BY a.publish_time desc limit ' . $offset . ',' . $limit;

        $result = $model->query($sql);
        return $result;
    }

    function getChanneCount($channel, $user_id, $media_type)
    {
        $model = M();
        $sql = 'select count(*) cnt from jrqg.stream_info where status="' . $channel . '"';
        $sql .= ' and user_id="' . $user_id . '"';
        $sql .= ' and media_type="' . $media_type . '"';
        $result = $model->query($sql);
        return $result[0]["cnt"];
    }

    function getChannelAll($search, $media_type, $offset, $limit = 20)
    {
        $model = M();
        $sql = 'select * from  jrqg.stream_info as stream_info';
        $sql .= ' left join jrqg.user_info as user_info on stream_info.user_id=user_info.user_id';
        $sql .= ' where stream_info.status=0 ';
        if (!empty($search)) {
            $sql .= ' and user_info.user_name like "%' . $search . '%"';
        }

        if ($media_type != "全部") {
            $sql .= ' and stream_info.media_type="' . $media_type . '"';
        }
        $sql .= ' ORDER BY stream_info.publish_time desc limit ' . $offset . ',' . $limit;
        $result = $model->query($sql);
        return $result;
    }

    function getChanneAllCount($search, $media_type)
    {
        $model = M();
        $sql = 'select count(*) cnt from  jrqg.stream_info as stream_info';
        $sql .= ' left join jrqg.user_info as user_info on stream_info.user_id=user_info.user_id';
        $sql .= ' where stream_info.status=0 ';
        if (!empty($search)) {
            $sql .= ' and user_info.user_name like "%' . $search . '%"';
        }
        if ($media_type != "全部") {
            $sql .= ' and stream_info.media_type="' . $media_type . '"';
        }
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
        $model->where($map)->save($arr);

    }

    public function updateMediaDetail($map, $arr)
    {
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

    public function updateGuanzhiMsg($map, $arr)
    {

        $model = M("jrqg.guanzhi_msg");
        $result = $model->where($map)->save($arr);
        return $result;
    }

    public function getGuanzhiChoiceTopic($map)
    {
        $model = M("jrqg.guanzhi_choice_topic");
        $result = $model->where($map)->select();
        return $result;
    }

    public function addGuanzhiChoiceTopic($arr)
    {
        $model = M("jrqg.guanzhi_choice_topic");
        $id = $model->add($arr);
        return $id;
    }

    public function updateGuanzhiChoiceTopic($map, $arr)
    {
        $model = M("jrqg.guanzhi_choice_topic");
        $result = $model->where($map)->save($arr);
        return $result;
    }


    public function addTagMedia($arr)
    {
        $model = M("jrqg.tag_media");
        $id = $model->add($arr);
        return $id;
    }

    public function getTagMedia($map)
    {
        $model = M("jrqg.tag_media");
        $result = $model->where($map)->select();
        return $result;
    }

    public function delTagMedia($map)
    {
        $model = M("jrqg.tag_media");
        $result = $model->where($map)->delete();
        return $result;
    }
}