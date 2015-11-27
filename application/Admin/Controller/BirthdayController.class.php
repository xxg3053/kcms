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
	function edit(){
		$id=I("get.id");
		$ad=$this->birthday_model->where("id=$id")->find();
		$this->assign($ad);
		$this->display();
	}
	
	function edit_post(){
		if (IS_POST) {
			if ($this->birthday_model->create()) {
				if ($this->birthday_model->save()!==false) {
					$this->success("保存成功！", U("birthday/index"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->birthday_model->getError());
			}
		}
	}
	
	/**
	 *  删除
	 */
	function delete(){
		$id = I("get.id",0,"intval");
		if ($this->birthday_model->delete($id)!==false) {
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}
	function checkBirthday(){
		$birthdays = $this->birthday_model->select();
		$count=count($birthdays);
		for($i = 0; $i < $count; $i++) {
			$name = $birthdays[$i]['user_name'];
			$birthday = $birthdays[$i]['user_birthday'];
			$email = $birthdays[$i]['user_email'];
			$d = birthday_difference_now($birthday);
			if($d > 0 && $d < 4){
				//提醒管理员
				sp_send_email('xxg3053@qq.com','KCMS系统生日提醒','您的好友'.$name.'生日是'.$birthday.',还有'.$d.'天就到了哦！赶紧祝福吧！！');
				//提醒好友
				sp_send_email($email,'KCMS系统生日提醒','亲爱的'.$name.'：您的生日快到了,还有'.$d.'天就到了哦！记住好好庆祝哦！');
			}elseif ($d == 0) {
				//提醒管理员
				sp_send_email('xxg3053@qq.com','KCMS系统生日提醒','您的好友'.$name.'生日是今天哦，赶紧祝福吧！！');
				//提醒好友
				sp_send_email($email,'KCMS系统生日提醒','亲爱的'.$name.'：您今天生日哦，赶紧HUPPY吧！！');
			}
			
		}
		$this->success('检查成功！');
	}


	


}