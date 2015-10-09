<?php
namespace Home\Controller;
use Home\Controller\CommonController;
/**
 * 搜索控制器
 */
class SearchController extends CommonController{
	/**
	 * 选择性的获取输入的关键字
	 */
	private function _getKeyword(){
		return I('keyword') == '搜索微博、找人' ? NULL : I('keyword');
	}
	/**
	 * 处理查询用户
	 */
	public function sechUser(){
		$keyword = $this -> _getKeyword();
		if ($keyword) {
			//检索出除自己外所有的用户
			$where = array(
				'username' => array('LIKE','%' . $keyword . '%'),
				'uid' => array('NEQ', $_SESSION['uid'])
				);
			$field = array('username', 'sex', 'location', 'intro', 'face80', 'follow', 'fans', 'weibo', 'uid');
			$db = M('userinfo');
			$count = $db->where($where)->count();// 查询满足要求的总记录数
			$page = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
			$limit = $page->firstRow .','. $page->listRows;
			$result = $db->where($where)->order('fans')->field($field)->limit($limit)->select();
			$result = $this->_getMutual($result);// 重组结果集
			$this->assign('result',$result);// 赋值数据集
			$this->assign('page',$page->show());// 赋值分页输出
		}
		$this->assign('keyword',$keyword);
		$this->display();
	}

	/**
	 * 重组结果集得到是否互相关注与是否已关注
	 * @param  [Array] $result [需要处理的结果集]
	 * @return [Array]         [处理完成后的结果集]
	 */
	private function _getMutual ($result) {
		if (!$result) return false;

		$db = M('follow');

		foreach ($result as $k => $v) {
			//是否互相关注
			$sql = '(SELECT `follow` FROM `tt_follow` WHERE `follow` = ' . $v['uid'] . ' AND `fans` = ' . $_SESSION['uid'] . ') UNION (SELECT `follow` FROM `tt_follow` WHERE `follow` = ' . $_SESSION['uid'] . ' AND `fans` = ' . $v['uid'] . ')';
			$mutual = $db->query($sql);
			
			if (count($mutual) == 2) {// 互相关注
				$result[$k]['mutual'] = 1;
				$result[$k]['followed'] = 1;
			} else {
				$result[$k]['mutual'] = 0;

				//未互相关注是检索是否已关注
				$where = array(
					'follow' => $v['uid'],
					'fans' => $_SESSION['uid']
					);
				$result[$k]['followed'] = $db->where($where)->count();
			}
		}
		return $result;
	}
}