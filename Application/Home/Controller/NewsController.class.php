<?php

namespace Home\Controller;

use Home\Model\ChannelInfoModel;
use Think\Controller;

class NewsController extends BaseController
{


    /***
     * display Area
     ***/
    public function newsShow()
    {
        $this->display("news");
    }



}

?>