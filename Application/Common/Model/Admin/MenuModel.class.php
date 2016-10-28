<?php
namespace Common\Model\Admin;

use Think\Model;


class MenuModel extends Model
{
    protected $tableName = 'etago_menu';

    /**
     * 根据权限获取对应菜单
     * @param array $permissions
     * @return array|bool
     */
    public function getListByPermissions($permissions = [])
    {
        if (!$list = $this->getList()) {
            return false;
        }

        $new_list = [];
        foreach ($list as $item) {
            if (in_array($item['permissions'], $permissions)) {
                $new_list[] = $item;
                continue;
            }
        }

        return $this->getSortList($new_list);
    }

    /**
     * 获取菜单列表
     * @param $offset
     * @param $limit
     * @param int $del_flag
     * @return mixed
     */
    public function getList($del_flag = 0)
    {
        $where['a.del_flag'] = $del_flag;
        return $this->alias('a')->where($where)->order('a.sort_order ASC')->select();


    }

    /**
     * 获取上下级关系的菜单数据
     * @param array $list 菜单数据
     * @return array|bool
     */
    public function getSortList($list = [])
    {
        if (empty($list)) {
            if (!$list = $this->getList()) {
                return false;
            }
        }

        $new_list = [];
        foreach ($list as $item) {
            if ($item['parent_id'] > 0) {
                $new_list[$item['parent_id']]['son'][] = $item;
            }

            if ($item['parent_id'] == 0) {
                if (isset($new_list[$item['id']])) {
                    $new_list[$item['id']] = array_merge($item, $new_list[$item['id']]);
                    continue;
                }
                $new_list[$item['id']] = $item;
            }


        }

        $new_list = sortResult($new_list, 'sort_order', 1);

        return $new_list;
    }


}

?>