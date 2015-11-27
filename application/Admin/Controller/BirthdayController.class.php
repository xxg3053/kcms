<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
use Common\Lib\Lunar;
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

		//$lunar=new Lunar();
		//$month=$lunar->convertSolarToLunar(date('Y'),date('m'),date('d'));
		//print_r($month);
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
		$lunar=new Lunar();
		$mainRemind = '';
		for($i = 0; $i < $count; $i++) {
			$name = $birthdays[$i]['user_name'];
			$birthday = $birthdays[$i]['user_birthday'];
			$email = $birthdays[$i]['user_email'];
			$solar = $birthdays[$i]['user_solar'];
			$remind = $birthdays[$i]['user_remind'];
			
			 $d = 0;
            if($solar==0){
                $l=$lunar->convertSolarToLunar(date('Y'),date('m'),date('d'));
                $lmounth = $l[4];
                $lday = $l[5];
                $lnow = date('Y').'-'.$lmounth.'-'.$lday;
                $d = date_difference_days($birthday,date($lnow));
            }else{
                $d = date_difference_days($birthday,date('Y-m-d'));
            }

			if($d > 0 && $d < 4){
				//提醒好友
				if($remind == 1){
					sp_send_email($email,'KCMS系统生日提醒','亲爱的'.$name.'：您的生日快到了,还有'.$d.'天就到了哦！记住好好庆祝哦！');
				}

				$mainRemind .= '您的好友'.$name.'的生日快到了，还有'.$d.'天\n\n\n';
			}elseif ($d == 0) {
				//提醒好友
				if($remind == 1){
					sp_send_email($email,'KCMS系统生日提醒','亲爱的'.$name.'：您今天生日哦，赶紧HUPPY吧！！');
				}
				$mainRemind .= '今天是您的好友'.$name.'的生日，发短信祝福吧！！\n\n\n';
			}
		}
		//提醒管理员
		if($mainRemind){
			sp_send_email('xxg3053@qq.com','所有好友生日提醒功能',$mainRemind);
		}
		
		$this->success('检查成功！');
	}


	


}