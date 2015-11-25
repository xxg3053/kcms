<?php
namespace Wechat\Controller;
use Common\Controller\AdminbaseController;
use Com\Wechat;
use Com\WechatAuth;

class FlatController extends AdminbaseController{

	function _initialize() {
		parent::_initialize();
		$this->flat_model = D("Common/WechatFlat");
	}

	function index(){
		$count=$this->flat_model->count();
		$page = $this->page($count, 10);
		$flats = $this->flat_model
		->order("id DESC")
		->limit($page->firstRow . ',' . $page->listRows)
		->select();

		$appid     = 'wx74d59fbce4fde733';
        $appsecret = '0d124c35cc812c85e4e1a0f00abb2fd3';

        $token = session("token");
        if($token){
            $auth = new WechatAuth($appid, $appsecret, $token);
        } else {
            $auth  = new WechatAuth($appid, $appsecret);
            $token = $auth->getAccessToken();
            session(array('expire' => $token['expires_in']));
            session("token", $token['access_token']);
        }
        //获取组
        $group = $auth->groupsGet();
        print_r($group);
        //发送邮件
        sp_send_email('xxg3053@qq.com','KCMS生日提醒','aaa');
		$this->assign("page", $page->show());
		$this->assign("flats",$flats);
		$this->display();

	}
	


}