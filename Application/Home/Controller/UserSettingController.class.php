<?php
namespace Home\Controller;
use Home\Controller\CommonController;
/**
 * 用户设置控制器
 */
class UserSettingController extends CommonController{
	
	/**
	 * 用户基本信息设置试图
	 */
	public function index(){
		$where = array('uid' => $_SESSION['uid']);
		$field = array('username', 'truename', 'sex', 'location', 'email', 'constellation', 'intro', 'face180');
		$user = M('userinfo')->field($field)->where($where)->find();
		$this -> assign(user,$user);
		$this -> display();
	}

	/**
	 * 处理基本信息提交
	 */
	public function editBasic(){
		if (!IS_POST) {
    		$this->error('页面不存在');
    	}
    	$data = array(
    		'username' => I('nickname'),
    		'truename' => I('truename'),
    		'sex' => I('sex', '', 'intval'),
    		'location' => I('province') . ' ' . I('city'),
    		'constellation' => I('night'),
    		'intro' => I('intro')
    		);
    	$where = array('uid' => $_SESSION['uid']);
    	if (M('userinfo')->where($where)->save($data)) {
    		$this->success('修改成功', U('index'));
    	} else {
    		$this->error('修改失败');
    	}
	}

	/**
	 * 修改用户头像
	 */
	public function editFace(){
		if (!IS_POST) {
    		$this->error('页面不存在');
    	}
    	// P($_POST);
	}

	/**
	 * 修改登录密码
	 */
	public function editPwd(){
		if (!IS_POST) {
    		$this->error('页面不存在');
    	}
    	P($_POST);
	}
}