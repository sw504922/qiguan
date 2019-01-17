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

    public function addUserInofo(){

        $arr["user_id"]=I("user_id");
        $arr["user_name"]=I("user_name");
        $arr["certi"]=1;
        $arr["photo"]="";
        $arr["mobile"]="";
        $arr["gender"]=1;
        $arr["introduction"]=I("user_name");
        $arr["password"]=md5(I("password"));
        $model = M("user_info");
        $model->add($arr);
    }









    public function auth_user(){
        $authModel=M("auth_group");
        $map["type"]=0;
        $group=$authModel->where($map)->select();
        $this->assign("group",$group);

        $rule=$this->getRules();
        $this->assign("rule",$rule);

        $this->display();
    }


    private function getRules(){
        $ruleModel=M("auth_rule");
        $map["level"]=1;
        $map["od_type"]=0;
        $rule=$ruleModel->where($map)->select();

        foreach ($rule as $key=>$val){
            $first[$key]["id"]=$val["id"];
            $first[$key]["title"]=$val["title"];


            $sid["sid"]=$val["id"];
            $second=$ruleModel->where($sid)->select();
            foreach ($second as $value){
                $first[$key]["level"][$value["id"]]["id"]=$value["id"];
                $first[$key]["level"][$value["id"]]["title"]=$value["title"];
                $first[$key]["level"][$value["id"]]["sid"]=$value["sid"];
            }
        }

        return $first;
    }

    /**
     *
     * @$accessModel:更新部门查看网站的权限
     ***/
    public function updateGroup(){
        $arr["rules"]=I("rules");
        $map["id"]=I("rowkey");
        $map["od_type"]=0;
        $accessModel=M("auth_group");

        $staus=$accessModel->where($map)->save($arr);
        $this->ajaxReturn($staus);
    }

    public function deltedGroup(){
        $accessModel=M("auth_group");
        $map["id"]=I("rowkey");
        $map["type"]=0;
        $result=$accessModel->where($map)->delete();
        $this->ajaxReturn($result);
    }
}