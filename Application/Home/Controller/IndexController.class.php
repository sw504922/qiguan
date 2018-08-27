<?php
namespace Home\Controller;
use Think\Controller;	
class IndexController extends BaseController
{
	public function index()
	{
		$this->redirect("../channel/channel");
	}

}
?>