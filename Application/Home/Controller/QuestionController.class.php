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

class QuestionController extends BaseController
{
    public function question_asked()
    {

        $this->display();
    }

    private $limit = 20;

    public function getAnswer()
    {

        $status = I("status");
        $new_page = I('new_page');
        if ($new_page == 0) {
            $new_page = 1;
        }
        $offset = ($new_page - 1) * $this->limit;
        $answerModel = new AnswerInfoModel();
        $streamer = new StreamInfoModel();
        $answerResult = $answerModel->getAnswerInfo($status, $offset, $this->limit);
        foreach ($answerResult as $key => $val) {
            $result[$key] = $val;
            $strMap["msg_id"] = $val["msg_id"];
            $title = $streamer->getStreamInfo($strMap);
            $result[$key]["title"] = $title[0]["title"];
        }

        $this->result = $result;

        $this->resultCount=$answerModel->getAnswerInfoCount($status);

        $this->new_page = $new_page;
        $this->viewCount = $this->limit;
        $data = $this->fetch("Question/get_question");
        $this->ajaxReturn($data);
    }

    public function setStatus()
    {
        $arr["status"] = I("status");
        $map["answer_id"] = I("id");
        $AnswerInfoModel = new AnswerInfoModel();

        $AnswerInfoModel->updateAnswerInfo($map, $arr);

    }

}