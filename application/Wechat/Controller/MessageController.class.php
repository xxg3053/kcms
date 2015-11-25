<?php
namespace Wechat\Controller;
use Common\Controller\AdminbaseController;
class MessageController extends AdminbaseController{

	function _initialize() {
		parent::_initialize();
		$this->message_model = D("Common/WechatMessage");
	}

	function index(){
		$count=$this->message_model->count();
		$page = $this->page($count, 10);
		$messages = $this->message_model
		->order("wt_createtime DESC")
		->limit($page->firstRow . ',' . $page->listRows)
		->select();
		$this->assign("page", $page->show());
		$this->assign("messages",$messages);
		$this->display();

	}
	


}