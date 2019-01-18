<?php
/**
 * Created by PhpStorm.
 * User: wei.sun
 * Date: 2017/8/14
 * Time: 11:43
 */

namespace Home\Controller;

use Think\Auth;
use Think\Controller;

use Think\Log;

class BaseController extends Controller
{


    protected function _initialize()
    {
        $session = session("qg_auth");

        if (empty($session)) {
            $this->redirect("Login/login");
        }

        $value = $session[0];
        $uid = $value['user_id'];
        $auth = new Auth();

        if (!$auth->check(MODULE_NAME . '/' . CONTROLLER_NAME, $uid)) {
           $this->redirect('Login/login');
        }

        $auth = new Auth();
        $model = M("jrqg.auth_rule");
        $result=$model->select();

        foreach ($result as $val){
            if ($auth->check( $val["name"], $uid)) {
                $length=strripos($val["name"],"/");
                $su=substr($val["name"],$length+1,strlen($val["name"]));
                $this->$su= $val["id"];
            }
        }


        $arr["auth"] =$value;

        $this->assign("arr", $arr);



    }






    /**
     * upload imgage
     ***/
    public function uploadMVI($Thumbail, $path, $path_subName,$saveName)
    {
        foreach ($Thumbail as $img) {
            $name = $img['name'];
        }
        log::write($path_subName . $path . $name . " is thunmail name");
        $upload = new \Think\Upload();
        $upload->maxSize = 0;
        $upload->exts = array(
            'jpg',
            'gif',
            'png',
            'jpeg',
            'mp3',
            'mp4',
        );
        $upload->rootPath = $path;
        $upload->subName = $path_subName;
        $upload->saveName = $saveName;
        $static_thum = $upload->upload($Thumbail);

    }

    /***
     * @ send SMS
     * */
    public function sendSMS($phone_number, $sms_content, $area_id)
    {
        $jsonHeader = '{"header": {
        "version": 1,
		"imei": "046097B8690EA0D2DDFC76CA05D957C8",
		"key_code": "1B1D9D39F50EE4302D65A3438FD43067",
		"ua": {
			"app_version": "1.6.4",
			"height": 1280,
			"model": "HM NOTE 1LTE",
			"os_version": "19",
			"platform": "android",
			"width": 720,
			"trader": "mining_t"
		},
		"user_type": 1,
		"user_name": "15699885506",
		"auth_code": "345C51A23487E33CC0E72601B855C1F2",
		"system_time": 1422263479031
	        }
        }';

        $jsonRequest = ' {"request_data" : {"info_type" : 1,"phone_number" : "13051514442","area_id" : "","verify_code" : "","sms_content":"504210"}}';

        $headerArr = json_decode($jsonHeader, true);
        $requestArr = json_decode($jsonRequest, true);
        if (!empty($phone_number)){
            $requestArr["request_data"]["phone_number"] = $phone_number;
        }
        if (!empty($sms_content)){
            $requestArr["request_data"]["sms_content"] = $sms_content;
        }

        $requestArr["request_data"]["area_id"] = $area_id;

        $newArr = array_merge($requestArr, $headerArr);

        $data = json_encode($newArr);
        $json = false;
        $url = "http://push.investassistant.com/miningpush/sms/sysSmsSend";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //发送JSON数据
        if ($json) {
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/json; charset=utf-8',
                    'Content-Length:' . strlen($data)));
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $res = curl_exec($curl);

        $errorno = curl_errno($curl);



        if ($errorno) {
            return array('errorno' => false, 'errmsg' => $errorno);
        }

        curl_close($curl);

        return json_decode($res, true);

    }


    function getMD($str){
        $rand=rand(1,1000);
        return strtolower(substr(md5($str.$rand),0,16));
    }


    function getPlayTime($path){
        Vendor('getid3.getid3');
        $getID3 = new \getID3 ();

        $ThisFileInfo = $getID3->analyze($path); //分析文件，$path为音频文件的地址

        $fileduration=$ThisFileInfo['playtime_seconds']; //这个获得的便是音频文件的时长
        $time = (int)ceil($fileduration);
        return $time;
    }
}