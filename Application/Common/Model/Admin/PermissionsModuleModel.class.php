<?php
namespace Common\Model\Admin;

use Think\Model;


class PermissionsModuleModel extends Model
{
    protected $tableName = 'etago_permissions_module';


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
        return $this->where($where)->order('sort ASC')->select();
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
        return $this->index($index)->where($where)->order('sort ASC')->select();
    }


}

?>