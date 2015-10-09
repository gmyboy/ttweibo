<?php
namespace Home\Model;
use Think\Model\ViewModel;
/**
 * 微博文章视图模型
 */
class ArticleViewModel extends ViewModel{
	//定义视图表关联关系
	protected $viewFields = array(
		'article' => array(
			'id', 'content', 'isturn', 'postime', 'turn', 'keep', 'comment', 'uid',
			'_type' => 'LEFT'
			),
		'userinfo' => array(
			'username', 'face50' => 'face',
			'_on' => 'article.uid = userinfo.uid',
			'_type' => 'LEFT'
			),
		'file' => array(
			'picture50', 'picture80', 'picture180',
			'_on' => 'article.id = file.aid'
			)
		);

	/**
	 * 返回查询返有记录
	 * @param  [type] $where [description]
	 * @return [type]        [description]
	 */
	public function getAll ($where, $limit) {
		$result = $this->where($where)->order('postime DESC')->limit($limit)->select();

		//重组结果集数组，得到转发微博
		if ($result) {
			foreach ($result as $k => $v) {
				if ($v['isturn']) {
					$tmp = $this->find($v['isturn']);
					$result[$k]['isturn'] = $tmp ? $tmp : -1;
				}
			}
		}
		return $result;
	}
}