<?php
/**
 * Created by date 2018/10/16.
 * Author: wei.sun
 * Type:
 ***/

namespace Home\Controller;


use Home\Model\AnswerInfoModel;
use Home\Model\DiscoverNoticeModel;
use Home\Model\StreamInfoModel;

class TopicController extends BaseController
{
    public function get_topic()
    {
        $model = M("topic");
        $map["status"] = 1;
        $result = $model->where($map)->select();
        $this->assign("result", $result);
        $this->display();
    }


    public function addTopic()
    {
        $arr["name"] = trim(I("title"));
        $arr["topic_desc"] = trim(I("desc"));
        $arr["status"] = 1;
        $model = M("topic");
        $model->add($arr);
    }

    public function deltedMethod(){
        $map["topic_id"]=I("id");
        $arr["status"] = 0;
        $model = M("topic");
        $model->where($map)->save($arr);
    }

    public function updateTopic(){
        $map["topic_id"]=I("topic_id");
        $arr["name"] = trim(I("editTitle"));
        $arr["topic_desc"] = trim(I("editDesc"));

        $model = M("topic");
        $model->where($map)->save($arr);
    }

}