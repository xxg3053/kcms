<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class BirthdayController extends AdminbaseController{

	function _initialize() {
		parent::_initialize();
		$this->birthday_model = D("Common/Birthday");
	}

	function index(){
		$count=$this->birthday_model->count();
		$page = $this->page($count, 20);
		$birthdays = $this->birthday_model
		->order("id ASC")
		->limit($page->firstRow . ',' . $page->listRows)
		->select();

		$this->assign("page", $page->show());
		$this->assign("birthdays",$birthdays);
		$this->display();

	}

	function add(){
		$this->display();
	}

	function add_post(){
		if(IS_POST){
			if ($this->birthday_model->create()) {
				$result=$this->birthday_model->add();
				if ($result!==false) {
					$this->success("添加成功！", U("birthday/index"));
				} else {
					$this->error("添加失败！");
				}
			} else {
				$this->error($this->birthday_model->getError());
			}
			
			
		}
	}

	function checkBirthday(){
		$birthdays = $this->birthday_model->select();
		$count=count($birthdays);
		for($i = 0; $i <= $count; $i++) {
			$bd = $birthdays[$i]['user_birthday'];
			$name = $birthdays[$i]['user_name'];
			$a=strtotime(date("Y-m-d"));
			$b=strtotime($bd);
			$c=$a-$b;
			$d=ceil($c/3600/24);
			if($d > 1 && $d < 4){
				sp_send_email('xxg3053@qq.com','KCMS生日提醒','您的好友'.$name.'生日是'.$bd.',还有'.$d.'天就到了哦！赶紧祝福吧！！');
			}
			
		}
		$this->success('检查成功！');
	}
	


}