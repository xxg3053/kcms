<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class BirthdayController extends AdminbaseController{

	function _initialize() {
		parent::_initialize();
		$this->birthday_model = D("Common/Birthday");
	}

	function index(){
		echo('index');
		$count=$this->birthday_model->count();
		$page = $this->page($count, 20);
		$birthdays = $this->birthday_model
		->order("create_time DESC")
		->limit($page->firstRow . ',' . $page->listRows)
		->select();

		$this->assign("page", $page->show());
		$this->assign("birthdays",$birthdays);
		$this->display();

	}

}