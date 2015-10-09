<?php
namespace Home\Model;
use Think\Model\RelationModel;
/**
 * 用户表关联模型
 */
class UserRelationModel extends RelationModel{

	protected $tableName = 'user';
    //定义用户与用户信息表关联关系属性
    protected $_link = array(
        'userinfo' => array(
            'mapping_type' => self::HAS_ONE,
            'foreign_key' => 'uid'
            )
        );

    /**
     * 自动插入的方法
     */
    public function insert ($data) {
        return $this->relation(true)->add($data);
    }
}
