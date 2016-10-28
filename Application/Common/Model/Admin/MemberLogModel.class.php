<?php
namespace Common\Model\Admin;

use Think\Model;


class MemberLogModel extends Model
{
    protected $tableName = 'etago_member_log';
    protected $insertFields = 'member_id,action,info,entity_id,module_id,add_time,del_flag,ip';

    public static function addLog($member_id, $action, $info = [])
    {
        $grep_action = [
            'admin/city/getcitylist',
            'admin/index',
        ];

        $action = strtolower(trim($action));
        if (in_array($action, $grep_action)) {
            return true;
        }

        //获取权限列表
        $permissions_model = new PermissionsModel();
        $permissions_list = $permissions_model->getList();
        foreach ($permissions_list as $item) {
            $key = strtolower($item['path']);
            $permissions_list_tmp[$key] = $item;
        }
        $model = new self();
        $model->member_id = $member_id;
        $model->action = $action;
        $model->module_id = $permissions_list_tmp[$action]['module_id'];
        $model->entity_id = intval($info['id']);
        $model->info = self::parseInfo($action, $info);
        $model->add_time = time();
        $model->del_flag = 0;
        $model->ip = $_SERVER['REMOTE_ADDR'];

        if ($id = $model->add()) {
            return $id;
        }
        return false;
    }

    protected function parseInfo($action, $info)
    {
        switch ($action) {
            case 'admin/login/index':
                $info['password'] = '*';
                break;
            default:

        }

        return json_encode($info);
    }

    public function getListByUserId($member_id, $offset = 0, $pagesize = 10)
    {
        $where['a.member_id'] = $member_id;
        return $this->getList($where, $offset, $pagesize);
    }

    public function getList($where = [], $offset = '', $pagesize = '')
    {
        if (!empty($where)) {
            $this->where($where);
        }

        if ($where['member_id']) {
            $where['m.member_id'] = $where['member_id'];
            unset($where['member_id']);
        }

        if ($offset >= 0 && $pagesize > 0) {
            $this->limit($offset, $pagesize);
        }

        return $this->alias('a')->field('a.*,m.id member_id,m.nickname')->join('LEFT JOIN etago_member as m ON m.id = a.member_id')->order('a.add_time DESC')->select();
    }

    public function countByUserId($member_id)
    {
        $where['member_id'] = $member_id;
        return $this->countList($where);
    }

    public function countList($where = [])
    {
        if (!empty($where)) {
            $this->where($where);
        }

        if ($where['member_id']) {
            $where['m.member_id'] = $where['member_id'];
            unset($where['member_id']);
        }

        return $this->alias('a')->order('a.add_time DESC')->count();
    }

}

?>