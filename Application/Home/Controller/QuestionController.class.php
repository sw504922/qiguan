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

    public function getContent()
    {

        $status = I("status");
        $new_page = I('new_page');
        if ($new_page == 0) {
            $new_page = 1;
        }
        $offset = ($new_page - 1) * $this->limit;
        $answerModel = new AnswerInfoModel();

        $result = $answerModel->getAnswerInfo($status, $offset, $this->limit);


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

    public function getCommot(){
        $map["answer_id"] = I("id");
    }

}