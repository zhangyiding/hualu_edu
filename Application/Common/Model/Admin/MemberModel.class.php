<?php
namespace Common\Model\Admin;

use Think\Model;


class MemberModel extends Model
{
    protected $tableName = 'etago_member';

    protected $insertFields = 'nickname,job_code,email,region_code,mobile,post,password,role_id,city_id,status,salt,add_time,update_time,del_flag';
    protected $updateFields = 'id,nickname,job_code,email,region_code,mobile,post,password,permissions,role_id,city_id,status,salt,add_time,update_time,del_flag';

    protected $_validate = array(
        array('nickname', 'require', '昵称不能为空', 1), //默认情况下用正则进行验证
        array('job_code', 'require', '工号不能为空', 1), //默认情况下用正则进行验证
        array('email', 'require', '邮箱不能为空', 1), //默认情况下用正则进行验证
        array('email', 'email', '邮箱格式不正确'), //默认情况下用正则进行验证
        array('email', '', '邮箱已存在！', 0, 'unique', 1), // 在新增的时候验证email字段是否唯一
        array('job_code', '', '工号已存在！', 0, 'unique', 1), // 在新增的时候验证job_code字段是否唯一
        array('region_code', 'require', '国家码不能为空', 1), //默认情况下用正则进行验证
        array('mobile', 'require', '手机号码不能为空', 1), //默认情况下用正则进行验证
        array('post', 'require', '职位不能为空', 1), //默认情况下用正则进行验证
        array('password', 'require', '密码不能为空', 1, '', 1), //默认情况下用正则进行验证
        array('password', '6,15', '密码长度为6-15', 2, 'length'), //默认情况下用正则进行验证
        array('role_id', 'require', '角色Id不能为空', 1), //默认情况下用正则进行验证
        array('city_id', 'require', '城市Id不能为空', 1), //默认情况下用正则进行验证
        array('id', 'require', '成员Id不能为空', 1, '', 2), //默认情况下用正则进行验证
    );


    protected $_auto = array(
        array('status', '1', 1),  // 新增的时候把status字段设置为1
        array('salt', 'generateSalt', 1, 'callback'), // 对password字段在新增和编辑的时候使md5函数处理
        array('password', 'encryptPwd', 1, 'callback'), // 对password字段在新增和编辑的时候使md5函数处理
        array('add_time', 'time', 1, 'function'), // 对update_time字段在更新的时候写入当前时间戳
        array('update_time', 'time', 3, 'function'), // 对update_time字段在更新的时候写入当前时间戳
        array('del_flag', '0', 1), // 对update_time字段在更新的时候写入当前时间戳
    );
    
    public $rules = array(
        array('email','require','邮箱不能为空', self::MUST_VALIDATE),
        array('email','require','邮箱不能重复',self::MUST_VALIDATE,'unique',self::MODEL_UPDATE),
        array('mobile','require', '手机号码不能为空', self::MUST_VALIDATE),
        array('password', 'require', '密码不能为空', self::MUST_VALIDATE, '', self::MODEL_UPDATE), //默认情况下用正则进行验证
        array('password', '6,15', '密码长度为6-15', 2, 'length') //默认情况下用正则进行验证
    );

    /**
     * 根据用户名获取用户信息
     * @param $username
     * @param int $del_flag
     * @return mixed
     */
    public function findByUsername($username, $del_flag = 0)
    {
        $where['username'] = $username;
        $where['del_flag'] = $del_flag;
        return $this->getOne($where);
    }

    /**
     * 获取用户信息
     * @param $where
     * @return mixed
     */
    public function getOne($where)
    {
        return $this->where($where)->find();
    }

    public function getInfo()
    {
        $result['user_id'] = $this->id;
        $result['nickname'] = $this->nickname;
        $result['email'] = $this->email;
        $result['region_code'] = $this->region_code;
        $result['mobile'] = $this->mobile;
        $result['job_code'] = $this->job_code;
        $result['face'] = $this->face;
        $result['post'] = $this->post;
        $result['city_id'] = $this->city_id;
        $result['role_id'] = $this->role_id;
        $result['add_time'] = $this->add_time;
        $result['last_login_time'] = $this->last_login_time;
        $result['last_login_ip'] = $this->last_login_ip;
        $result['reg_time'] = $this->reg_time;

        $permissions_model = new PermissionsModel();
        //获取用户角色权限
        $role_model = new RoleModel();
        $role_permissions = $role_model->getRolePermissionsById($this->role_id);
        $role_permissions_list = $permissions_model->getListById($role_permissions);

        //合并权限
        $permissions = [];
        if ($role_permissions == '*') {
            $permissions = $role_permissions_list;
        } else {
            //获取用户权限
            $user_permissions_list = $permissions_model->getListById(explode(',', $this->permissions));
            //取用户组及用户权限并集
            $permissions = $role_permissions_list + $user_permissions_list;
        }

        $result['permissions'] = array_keys($permissions);
        $result['permissions_list'] = array_values($permissions);
        return $result;
    }

    /**
     * 根据Email获取用户信息
     * @param $email
     * @param int $del_flag
     * @return mixed
     */
    public function findByEmail($email, $del_flag = 0)
    {
        $where['email'] = $email;
        $where['del_flag'] = $del_flag;
        return $this->getOne($where);
    }

    /**
     * 校验密码
     * @param $password
     * @return bool
     */
    public function confirmPwd($password)
    {
        return $this->encryptPwd($password) === $this->password;
    }

    /**
     * 加密密码
     * @param $password
     * @return string
     */
    public function encryptPwd($password)
    {
        return md5(md5($password) . $this->salt);
    }

    /**
     * 获取用户列表
     * @param $offset
     * @param $limit
     * @param int $del_flag
     * @return mixed
     */
    public function getUserList($offset, $limit, $where = [])
    {
        return $this->where($where)->limit($offset, $limit)->order('id ASC')->select();
    }

    /**
     * 计算用户总数
     * @param int $del_flag
     * @return mixed
     */
    public function countUser($where = [])
    {
        return $this->where($where)->count();
    }

    public function getMembers($map, $pagesize, $offset, &$count)
    {
        $field = array(
            'm.id' => 'user_id',
            'm.job_code',
            'm.nickname',
            'c.name' => 'city_name',
            'm.post',
            'm.email',
            'm.mobile',
            'm.wechat'
        );
        $where = array();
        foreach ($map as $key => $value) {
            if (!empty($value)) {
                //处理模糊查询
                if ($key != 'city_id') {
                    $where[$key] = array('like', "%{$value}%");
                } else {
                    $where[$key] = $value;
                }
            }
        }
        $where['del_flag'] = 0;

        //当前记录个数
        $count = $this->where($where)->count();

        $result = $this->table('etago_admin.etago_member')->alias('m')->join('left join etago.etago_city as c on m.city_id = c.id')->field($field)->where($where)->limit($offset,
            $pagesize)->fetchSql(false)->select();
        //处理null
        if ($result) {
            $m_res = array();
            foreach ($result as $position => $arr) {
                foreach ($arr as $key => $value) {
                    $m_res[$position][$key] = toString($value);
                }
            }
            return $m_res;
        } else {
            return $result;
        }
    }

    public function getMemberByUserId($user_id)
    {
        $field = array(
            'm.id' => 'user_id',
            'm.job_code',
            'm.nickname',
            'c.name' => 'city_name',
            'm.post',
            'm.email',
            'm.mobile',
            'm.wechat',
            'm.password'
        );
        $where['m.del_flag'] = 0;
        $where['m.id'] = $user_id;
        $result = $this->table('etago_admin.etago_member')->alias('m')->join('left join etago.etago_city as c on m.city_id = c.id')->field($field)->where($where)->find();
        if ($result) {
            $m_res = array();
            foreach ($result as $key => $value) {
                $m_res[$key] = toString($value);
            }
            return $m_res;
        } else {
            return false;
        }
    }

    public function updateMember($user_id, $map)
    {
        if (!empty($map)) {//更新数据
            $map['id'] = $user_id;
            if (!$this->validate($this->rules)->create($map, 2)) {
                // 如果创建失败 表示验证没有通过 输出错误提示信息
//                 exit($this->getError());
                return false;
            } else {
                $this->salt = $this->generateSalt();
                $this->password = $this->encryptPwd($map['password']);
                $result = $this->save();
                return $result;
            }
        }
    }

    /**
     * 计算每个角色组成员数
     * @param string $role_id
     * @return array
     */
    public function countByRole($role_id=''){
        $where = [];
        if($role_id){
            $where['role_id'] = $role_id;
        }

        $result = [];
        if($result = $this->index('role_id')->field('count(*) as count,role_id')->where($where)->group('role_id')->select()){
            foreach ($result as &$item){
                $item = $item['count'];
            }
        }

        return $result;
    }

    /**
     * 生成新密码
     * @param $password
     * @return string
     */
    public function generateSalt()
    {
        return $this->salt = uniqid();
    }

}

?>