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
		$this->assign("page", $page->show());
		$this->assign("flats",$flats);
		$this->display();

	}

	function add(){
		$this->display();
	}

	function add_post(){
		if(IS_POST){
			if ($this->flat_model->create()) {
				$result=$this->flat_model->add();
				if ($result!==false) {
					$this->success("添加成功！", U("flat/index"));
				} else {
					$this->error("添加失败！");
				}
			} else {
				$this->error($this->flat_model->getError());
			}
			
			
		}
	}
	function edit(){
		$id=I("get.id");
		$ad=$this->flat_model->where("id=$id")->find();
		$this->assign($ad);
		$this->display();
	}
	function edit_post(){
		if (IS_POST) {
			if ($this->flat_model->create()) {
				if ($this->flat_model->save()!==false) {
					$this->success("保存成功！", U("flat/index"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->flat_model->getError());
			}
		}
	}
	
	/**
	 *  删除
	 */
	function delete(){
		$id = I("get.id",0,"intval");
		if ($this->flat_model->delete($id)!==false) {
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}
	/**
	 * 启用
	 * @Author   KENFO
	 * @Email    xxg3053@qq.com
	 * @DateTime 2015-11-26T21:05:41+0800
	 * @Describe
	 * @return   [type]                   [description]
	 */
	function startup(){
		$id=I("get.id");
		$data['wt_use']=1;
		if ($this->flat_model->where("id = $id")->save($data)!==false) {
			$data2['wt_use']=0;
			if ($this->flat_model->where("id != $id")->save($data2)!==false) {
				$this->success("启用成功！");
			}else{
				$this->error("启用失败！");
			}
			
		} else {
			$this->error("启用失败！");
		}
	}


	function detail(){
		$id=I("get.id");
		$flat = $this->flat_model->where("id = $id")->find();

        $appid     = $flat['wt_appid'];
        $appsecret = $flat['wt_appsecret'];

        $auth  = new WechatAuth($appid, $appsecret);
        ////获取组
        $group = $auth->userGet();//groupsGet();
        $this->assign("group",$group);
		$this->display();
	}

}