<?php
namespace Common\Model;
use Common\Model\CommonModel;
class BirthdayModel extends CommonModel{
	//自动验证
	protected $_validate = array(
			//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
			array('user_name', 'require', '姓名不能为空！', 1, 'regex', CommonModel:: MODEL_BOTH )
	);
	
	protected $_auto = array(
			array('create_time','mDate',1,'callback'), // 对msg字段在新增的时候回调htmlspecialchars方法
	);
	
	function mDate(){
		return date("Y-m-d H:i:s");
	}

	protected function _before_write(&$data) {
		parent::_before_write($data);
	}
}