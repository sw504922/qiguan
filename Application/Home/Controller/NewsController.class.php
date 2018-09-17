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

    public function photo()
    {
        $this->display();
    }

    public function music()
    {
        $this->display();
    }

    public function video()
    {
        $this->display();
    }

    public function content()
    {
        $this->display();
    }

    public function report()
    {
        $this->display();
    }

    public function comments()
    {
        $this->display();
    }

    public function jingxuan()
    {
        $this->display();
    }

    public function youli()
    {
        $this->display();
    }

    public function wendao()
    {
        $this->display();
    }

    public function bolan()
    {
        $this->display();
    }



    /*
     *
     *
     * */

}

?>