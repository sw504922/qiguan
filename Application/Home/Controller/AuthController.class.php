<?php
namespace Home\Controller;
use function Sodium\add;
use Think\Controller;

class AuthController extends BaseController
{

    public function user_page()
    {
        $model=M("user");
        $map["status"]=0;
        $map["type"]=1;
        $user=$model->where($map)->select();
        $this->assign("user",$user);
        $this->display("user_page");
    }

    public function add_user()
    {
        $model=M("auth_group");
        $map["type"]=1;
        $group=$model->where($map)->select();
        $this->assign("group",$group);
        $this->display("add_user");
    }

    public function auth_group(){
        $authModel=M("auth_group");
        $map["type"]=1;
        $group=$authModel->where($map)->select();
        $this->assign("group",$group);

        $rule=$this->getRules();
        $this->assign("rule",$rule);

        $this->display("auth_group");
    }

    public function add_group(){
        $rule=$this->getRules();
        $this->assign("rule",$rule);
        $this->display("add_group");
    }


    private function getRules(){
        $ruleModel=M("auth_rule");
        $map["level"]=1;
        $map["od_type"]=1;
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
     * @$userModel:添加新用户
     * @$accessModel:添加新用户所属组
     ***/
    public function addUser(){
        $arr["name"]=I("userName");
        $arr["login"]=I("login");
        $arr["password"]="e10adc3949ba59abbe56e057f20f883e";
        $userModel=M("user");
        $result=$userModel->add($arr);
        if($result!=""){
            $accessModel=M("auth_group_access");
            $map["uid"]=$result;
            $map["group_id"]=I("groupId");
            $accessModel->add($map);
        }
        $this->ajaxReturn($result);
    }

    /**
     *
     * @$accessModel:更新新用户所属组
     ***/
    public function updateAuthAccess(){
        $arr["group_id"]=I("groupId");
        $accessModel=M("auth_group_access");
        $map["uid"]=I("user");;
        $result=$accessModel->where($map)->save($arr);
        $this->ajaxReturn($result);
    }

    /**
     *
     * @$accessModel:更新部门查看网站的权限
     ***/
    public function updateGroup(){
        $arr["rules"]=I("rules");
        $map["id"]=I("rowkey");
        $map["od_type"]=1;
        $accessModel=M("auth_group");

        $accessModel->where($map)->save($arr);
    }
    /**
     *
     * @$accessModel:添加部门
     ***/
    public function addGroup(){
        $arr["rules"]=I("rules");
        $arr["title"]=I("title");
        $accessModel=M("auth_group");
        $result= $accessModel->add($arr);
        $this->ajaxReturn($result);
    }

    /**
     * @修改用户所属组
     * $model:获取用户信息
     * $authModel:获取部门信息
     ***/
    public function update_asscess_page()
    {
        $model=M("user");
        $arr["id"]=I("rowkey");
        $user=$model->where($arr)->select();
        $this->assign("user",$user);

        $authModel=M("auth_group");
        $map["type"]=1;
        $group=$authModel->where($map)->select();
        $this->assign("group",$group);


        $accessModel=M("auth_group_access");
        $map["uid"]=I("rowkey");
        $assceeUser=$accessModel->where($map)->select();

        $this->assign("assceeUser",$assceeUser);

        $this->display();
    }

    public function deltedGroup(){
        $accessModel=M("auth_group");
        $map["id"]=I("rowkey");
        $map["type"]=1;
        $result=$accessModel->where($map)->delete();
        $this->ajaxReturn($result);
    }


    public function deltedUser(){
        $userModel=M("user");
        $map["id"]=I("rowkey");
        $map["type"]=1;
        $result=$userModel->where($map)->delete();

        $accessModel=M("auth_group_access");
        $arr["uid"]=I("rowkey");
        $accessModel->where($arr)->delete();

        $this->ajaxReturn($result);
    }
}