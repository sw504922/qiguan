<?php

namespace Home\Controller;

use Think\Controller;

class LoginController extends Controller {

    public function login() {
        if (IS_POST) {
            $user = M('user_info');
            $name = I("user");
            $password = I("pwd");
            $map['password'] = md5($password);
            $map['user_name'] = $name;
            $result = $user->where($map)->select();

            if ($result == true) {
                $group=M("auth_group_access");
                $gMap["uid"]=$result[0]["user_id"];
                $groupId=$group->where($gMap)->select();
                session("qg_auth", $result);
                session("qg_group", $groupId[0]);
                cookie("login-cook-date",md5(date("Y-m-d")));
                $this->redirect('Index/index');
            } else {
                $this->error('密码或用户错误', U('Login/login'));
            }
        }
        $this->display();
    }


    public function logout() {
        session('[destroy]');
        cookie('login-cook-date','');
        $this->redirect('Login/login');

    }

    /*
    * 修改密码方法
    *
    */
    public function check()
    {
        $value = session("qg_auth");
        $map["id"]=$value[0]["id"];
        $api = M('user_info');
        $password = I('password');
        $data['password'] =$password;
        $chenk = $api->where($map)->save($data);

        if ($chenk == true) {
            session('[destroy]');
            $data= 0;
        }
        $this->AjaxReturn($data);
    }


}
