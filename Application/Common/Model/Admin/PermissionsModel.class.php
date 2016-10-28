<?php
namespace Common\Model\Admin;

use Think\Model;


class PermissionsModel extends Model
{
    protected $tableName = 'etago_permissions';


    /**
     * 获取权限列表
     * @param $offset
     * @param $limit
     * @param int $del_flag
     * @return mixed
     */
    public function getList($del_flag = 0)
    {
        $where['del_flag'] = $del_flag;
        return $this->where($where)->order('add_time ASC')->select();
    }

    /**
     * 获取权限列表
     * @param $offset
     * @param $limit
     * @param int $del_flag
     * @return mixed
     */
    public function getListWithIndex($del_flag = 0,$index='id')
    {
        $where['del_flag'] = $del_flag;
        return $this->index($index)->where($where)->order('add_time ASC')->select();
    }

    /**
     * 获取权限列表
     * @param $offset
     * @param $limit
     * @param int $del_flag
     * @return mixed
     */
    public function getListById($id, $del_flag = 0)
    {
        $where['id'] = $id;
        $where['del_flag'] = $del_flag;

        if (is_array($id)) {
            $where['id'] = ['in', $id];
        }elseif ($id == '*'){
            $where['id'] = ['GT', 0];
        }

        return $this->index('id')->where($where)->order('add_time ASC')->select();
    }


}

?>