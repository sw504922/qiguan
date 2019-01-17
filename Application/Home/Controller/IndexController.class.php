<?php

namespace Home\Controller;

use Think\Controller;

class IndexController extends BaseController
{
    public function index()
    {
        $session = session("qg_auth");
        $this->redirect("../News/getarticle");
    }

}

?>