<?php
namespace Home\Controller;
use Think\Controller;
/**
* 通用控制器
*/
class CommonController extends Controller {
	/**
	 *  自动运行的方法
	 */
	public function _initialize() {

		//处理自动登录
		if (isset($_COOKIE['auto']) && !isset($_SESSION['uid'])) {
			$value = explode('|', encryption($_COOKIE['auto'], 1));
			$ip = get_client_ip();

			//本次登录IP与上一次登录IP一致时
			if ($ip == $value[1]) {
				$account = $value[0];
				$where = array('account' => $account);

				$user = M('user')->where($where)->field(array('id', 'lock'))->find();
				
				//检索出用户信息并且该用户没有被锁定时，保存登录状态
				if ($user && !$user['lock']) {
					session('uid', $user['id']);
				}
			}
		}
		//判断用户是否已登录
		if (!isset($_SESSION['uid'])) {
			redirect(U('Login/index'));
		}
	}

	/**
	 * 头像上传
	 */
	public function uploadFace() {
		if (!IS_POST) {
    		$this->error('页面不存在');
    	}
		$upload = $this->_upload('Face', '180,80,50', '180,80,50');
		echo json_encode($upload);
	}

	/**
	 * 微博图片上传
	 */
	public function uploadPic () {
		if (!IS_POST) {
    		$this->error('页面不存在');
    	}
		$upload = $this->_upload('Pic', '800,380,120', '800,380,120');
		echo json_encode($upload);
	}

	/**
	 * 异步创建新分组
	 */
	public function addGroup () {
		if (!IS_POST) {
    		$this->error('页面不存在');
    	}
		$data = array(
			'name' => I('name'),
			'uid' => $_SESSION['uid']);
		if (M('group')->data($data)->add()) {
			echo json_encode(array('status' => 1, 'msg' => '创建成功'));
		} else {
			echo json_encode(array('status' => 0, 'msg' => '创建失败,请重试...'));
		}
	}

	/**
	 * 异步添加关注
	 */
	public function addFollow () {
		if (!IS_POST) {
    		$this->error('页面不存在');
    	}
		$data = array(
			'follow' => I('follow','', 'intval'),
			'fans' => $_SESSION['uid'],
			'gid' => I('gid','', 'intval')
			);
		if (M('follow')->data($data)->add()) {
			$db = M('userinfo');
			$db->where(array('uid' => $data['follow']))->setInc('fans');//别人粉丝+1
			$db->where(array('uid' => $_SESSION['uid']))->setInc('follow');//我的关注+1
			echo json_encode(array('status' => 1, 'msg' => '关注成功'));
		} else {
			echo json_encode(array('status' => 0, 'msg' => '关注失败请重试...'));
		}
	}

	/**
	 * 异步移除关注与粉丝
	 */
	public function delFollow () {
		if (!IS_POST) {
    		$this->error('页面不存在');
    	}

		$uid = I('uid','', 'intval');
		$type = I('type','', 'intval');

		$where = $type ? array('follow' => $uid, 'fans' => $_SESSION['uid']) : array('fans' => $uid, 'follow' => $_SESSION['uid']);

		if (M('follow')->where($where)->delete()) {
			$db = M('userinfo');
			if ($type) {
				$db->where(array('uid' => $_SESSION['uid']))->setDec('follow');//我的关注数-1
				$db->where(array('uid' => $uid))->setDec('fans');//好友的粉丝数-1
			} else {
				$db->where(array('uid' => $_SESSION['uid']))->setDec('fans');
				$db->where(array('uid' => $uid))->setDec('follow');
			}

			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * 图片上传处理方法
	 * @param  [String] $path   [保存文件夹名称]
	 * @param  [String] $width  [缩略图宽度多个用，号分隔]
	 * @param  [String] $height [缩略图高度多个用，号分隔(要与宽度一一对应)]
	 * @return [Array]         [图片上传信息]
	 */
	private function _upload($path, $width, $height) {
		$config = array(
		    'maxSize'    =>    C('UPLOAD_MAX_SIZE'),
		    'rootPath'   =>    C('UPLOAD_PATH'),
		    'savePath'   =>    $path . '/',
		    'saveName'   =>    array('uniqid',''),
		    'replace'    =>    true,
		    'exts'       =>    C('UPLOAD_EXTS'),
		    'autoSub'    =>    true,
		    'subName'    =>    array('date','Y_m'),
		);
		$obj = new \Think\Upload($config);// 实例化上传类


		// $obj->thumb = true;	//生成缩略图
		// $obj->thumbMaxWidth = $width;	//缩略图宽度
		// $obj->thumbMaxHeight = $height;	//缩略图高度
		// $obj->thumbPrefix = 'max_,medium_,mini_';	//缩略图后缀名
		// $obj->thumbPath = $obj->savePath . date('Y_m') . '/';	//缩略图保存图径
		// $obj->thumbRemoveOrigin = true;	//删除原图


		$info = $obj->upload();
		if (!$info) {
			return array('status' => 0, 'msg' => $obj->getError());
		} else {
			$image = new \Think\Image();
			$originPath =$info['Filedata']['savepath'] . $info['Filedata']['savename'];
			$minPath =  $info['Filedata']['savepath'] . 'min_' . $info['Filedata']['savename'];
			$mediumPath = $info['Filedata']['savepath'] . 'medium_' . $info['Filedata']['savename'];
			$maxPath = $info['Filedata']['savepath'] . 'max_' . $info['Filedata']['savename'];
			//打开原图
			$image->open('./Uploads/' . $originPath);
			// // 生成一个居中裁剪为150*150的缩略图并保存到指定路径
			$image->thumb('180', '180',\Think\Image::IMAGE_THUMB_SCALE)->save('./Uploads/' . $maxPath);
			

			return array(
				'status' => 1,
				'path'   => array(
					'min'    => $minPath,
					'medium' => $mediumPath,
					'max'    => $maxPath
					)
				);
		}
	}
}