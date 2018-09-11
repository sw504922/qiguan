<?php
namespace Home\Controller;
use function Sodium\add;
use Think\Controller;

class AuthController extends BaseController
{

    public function user()
    {
        $model=M("user");
        $accessModel=M("auth_group_access");
        $auth_model=M("auth_group");


        $map["status"]=0;
        $map["type"]=1;
        $user=$model->where($map)->select();

        foreach ($user as $key=>$val){
            $mapAccess["uid"]=$val["id"];
            $assceeUser=$accessModel->where($mapAccess)->select();
            $user[$key]["group_id"]= $assceeUser[0]["group_id"];

        }

        $this->assign("user",$user);

        $gmap["type"]=1;
        $group=$auth_model->where($gmap)->select();
        $this->assign("group",$group);


        $this->display("user");
    }




    /**
     * @$userModel:添加新用户
     * @$accessModel:添加新用户所属组
     ***/
    public function addUser(){
        $arr["name"]=I("userName");
        $arr["login"]=I("login");
        $arr["type"]=1;
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
        $arr["group_id"]=I("desc");
        $accessModel=M("auth_group_access");
        $map["uid"]=I("wechat");;
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