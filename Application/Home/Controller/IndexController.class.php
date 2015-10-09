<?php
namespace Home\Controller;
use Home\Controller\CommonController;
/**
 * 首页控制器
 */
class IndexController extends CommonController{
    /**
     * 首页视图
     */
    public function index() {
        //实例化微博视图模型
        $db = D('ArticleView');
        //取得当前用户的ID与当前用户所有关注好友的ID
        $uid = array(session('uid'));
        $where = array('fans' => session('uid'));
        $result = M('follow')->field('follow')->where($where)->select();
        if ($result) {
            foreach ($result as $v) {
                $uid[] = $v['follow'];
            }
        }

        //组合WHERE条件,条件为当前用户自身的ID与当前用户所有关注好友的ID
        $where = array('uid' => array('IN', $uid));
        //统计数据总条数，用于分页
        $count = $db->where($where)->count();
        $page = new \Think\Page($count,10);
        $limit = $page->firstRow .','. $page->listRows;
        //读取所有微博
        $result = $db->getAll($where, $limit);
        $this->assign('weibo',$result);
        $this->assign('page',$page->show());// 赋值分页输出
        $this->display();
    }
    
    /**
     * 微博发布处理
     */
    public function sendWeibo () {
        if (!IS_POST) {
            $this->error('页面不存在');
        } 
        
        $data = array(
            'content' => I('content'),
            'postime' => time(),
            'uid' => $_SESSION['uid']
            );
        
        if ($wid = M('article')->data($data)->add()) {
            if (!empty($_POST['max'])) {
                $img = array(
                    'mini' => $this->_post('mini'),
                    'medium' => $this->_post('medium'),
                    'max' => $this->_post('max'),
                    'wid' => $wid
                    );
                M('picture')->data($img)->add();
            }
            M('userinfo')->where(array('uid' => $_SESSION['uid']))->setInc('weibo');

            //处理@用户
            // $this->_atmeHandel($data['content'], $wid);

            $this->success('发布成功', $_SERVER['HTTP_REFERER']);
        } else {
            $this->error('发布失败请重试...');
        }
    }
    /**
     * 退出登录
     */
    public function logout() {
        // 清空session
        session('uid', null);
        session('[destroy]');
        
        //删除用于自动登录的COOKIE
        @setcookie('auto', '', time() - 3600, '/');
        header('Content-Type:text/html;Charset=UTF-8');
        redirect(__APP__, 3, '注销成功，正在为您跳转...');
    }
}
