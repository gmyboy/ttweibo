<?php
namespace Home\Controller;
use Think\Controller;
/**
* 用户登录注册控制器
*/
class LoginController extends Controller {
    public function index(){
    	$this->display();
    }

    public function register(){
    	$this->display();
    }

    /**
     * 处理登陆表单
     */
    public function login(){
    	if (!IS_POST) {
    		$this->error('页面不存在');
    	}
    	$pwd = I('pwd','','md5');
    	$where = array('account' => I('account'));

    	$user = M('user')->where($where)->find();
		if (!$user || $user['password'] != $pwd) {
			$this->error('用户名或者密码不正确');
		}

		if ($user['lock']) {
			$this->error('用户被锁定');
		}

		//处理下一次自动登录
		if (isset($_POST['auto'])) {
			$account = $user['account'];
			$ip = get_client_ip();
			$value = $account . '|' . $ip;
			$value = encryption($value);
			@setcookie('auto', $value, C('AUTO_LOGIN_TIME'), '/');
		}

		//登录成功写入SESSION并且跳转到首页
		session('uid', $user['id']);

		header('Content-Type:text/html;Charset=UTF-8');
		redirect(__APP__, 3, '登录成功，正在为您跳转...');
    }

    /**
     * 处理注册表单
     */
    public function runRegist(){
    	if (!IS_POST) {
    		$this->error('页面不存在');
    	}
		//验证验证码
        // $check_verify = new \Think\Verify();
        // if (!$check_verify->check(I('verify'))) {
        //     $this->error('验证码错误');
        // }
        //验证两次密码是否一致
        if (I('pwd') != I('pwded')) {
            $this->error('两次密码不一致');
        }

    	$data = array(
    		'account' => I('account'),
    		'password' => I('pwd','','md5'),
    		'registime' => $_SERVER['REQUEST_TIME'],
    		'userinfo' => array(
    			'username' => I('uname'),
                'email' => I('email')
    			)
    		);
    	$id = D('UserRelation')->insert($data);
    	if ($id) {
    		//插入数据成功后把用户ID写SESSION
            session('uid', $id);
            //跳转至登陆首页
            header('Content-Type:text/html;Charset=UTF-8');
            redirect(U('index'), 3, '注册成功，快去登陆吧...');
        } else {
            $this->error('注册失败，请重试...');
        }
    }

    /**
     * 异步验证账号是否已存在
     */
    public function checkAccount(){
    	if (!IS_POST) {
    		$this->error('页面不存在');
    	}
    	$where = array('account' =>I('account'));
    	if (M('user')->where($where)->getField('id')) {
    		echo 'false';
    	}else{
    		echo 'true';
    	}
    }

    /**
     * 异步检查昵称
     */
    public function checkUname(){
    	if (!IS_POST) {
    		$this->error('页面不存在');
    	}
    	$where = array('username' =>I('uname'));
    	if (M('userinfo')->where($where)->getField('id')) {
    		echo 'false';
    	}else{
    		echo 'true';
    	}
    }

    /**
     * 异步检查验证码
     */
    public function checkVerify(){
    	$check_verify = new \Think\Verify();
        if (!$check_verify->check(I('verify'))) {
            echo 'false';
        }else{
    		echo 'true';
    	}
    }

    /**
     * 初始化验证码
     */
    public function verify(){
    	$config = array(
        'fontSize' => 47,// 验证码字体大小
        'length' => 4,   // 验证码位数
        'useNoise' => false,// 关闭验证码杂点
        );
        $verify = new \Think\Verify($config);
        // $Verify->useImgBg = true; //使用背景图片
        $verify->entry();
    }
}