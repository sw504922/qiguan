<?php

namespace Home\Controller;

use function Sodium\add;
use Think\Controller;

class AuthController extends BaseController
{

    public function user()
    {
        $model = M("user_info");
        $map["certi"] = 1;
        $result = $model->where($map)->select();
        foreach ($result as $key => $val) {
            $user_id = $val["user_id"];
            if (strlen($user_id) != 8) {
                $arr[$key] = $val;

            }

        }
        $this->assign("user", $arr);
        $this->display();
    }


    public function updateUserInfo()
    {
        $arr["user_name"] = I("user_name");
        $user_id = I("user_id");
        $arr["mobile"] = I("mobile");
        $model = M("user_info");
        $map["user_id"] = $user_id;
        $model->where($map)->save($arr);
    }


    public function deletedUserInfo()
    {
        $user_id = I("user_id");
        $map["user_id"] = $user_id;
        $model = M("user_info");
        $model->where($map)->delete();
    }
}