<?php

namespace Home\Controller;

use Think\Controller;

class IndexController extends BaseController
{
    public function index()
    {
        $session = session("od_auth");
        $this->redirect("../News/newsShow");
    }

}

?>