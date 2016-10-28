<?php
namespace Common\Model\Admin;

use Think\Model;


class RoleModel extends Model
{
    protected $tableName = 'etago_role';

    protected $insertFields = 'name,add_time,update_time,del_flag';
    protected $updateFields = 'id,name,permissions,update_time,del_flag';

    protected $_validate = array(
        array('name', 'require', '管理组名称不能为空', 1), //默认情况下用正则进行验证
        array('name', '', '管理组名称已存在！', 0, 'unique', 1), // 在新增的时候验证name字段是否唯一
        array('id', 'require', '角色id不能为空', 1, '', 2), //默认情况下用正则进行验证
    );

    protected $_auto = array(
        array('add_time', 'time', 1, 'function'), // 对update_time字段在更新的时候写入当前时间戳
        array('update_time', 'time', 3, 'function'), // 对update_time字段在更新的时候写入当前时间戳
        array('del_flag', '0', 1), // 对update_time字段在更新的时候写入当前时间戳
    );

    /**
     * 获取角色权限信息
     * @param $role_id
     * @return bool
     */
    public function getRolePermissionsById($role_id)
    {
        if (!$role = $this->getOne(['id' => $role_id])) {
            return false;
        }

        return $role['permissions'];
    }

    /**
     * 获取角色信息
     * @param $where
     * @return mixed
     */
    public function getOne($where)
    {
        return $this->where($where)->find();
    }

    /**
     * 获取用户列表
     * @param $offset
     * @param $limit
     * @param int $del_flag
     * @return mixed
     */
    public function getList($offset = '', $limit = '', $index = 'id')
    {
        if ($offset >= 0 && $limit > 0) {
            return $this->index($index)->limit($offset, $limit)->order('id ASC')->select();
        }
        return $this->index($index)->order('id ASC')->select();
    }

    /**
     * 计算用户总数
     * @param int $del_flag
     * @return mixed
     */
    public function countAll($del_flag = 0)
    {
        $where['del_flag'] = $del_flag;
        return $this->where($where)->count();
    }

}

?>