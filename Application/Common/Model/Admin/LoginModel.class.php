<?php
namespace Common\Model\Admin;

use Think\Model;

class LoginModel extends Model
{


    protected $type = '';
    protected $username;
    protected $password;
    protected $option;
    protected $user;

    /**
     * 用户登录
     * @param $username
     * @param $password
     * @param array $option
     * @return mixed
     */
    public function login($username, $password, $option = [])
    {

        $this->option = $option;
        $this->username = $username;
        $this->password = $password;

        if (strripos($username, '@') != false) {
            return $this->loginByEmail();
        } elseif (intval($username) > 0 && strlen($username) > 10) {
            return $this->loginByMobile();
        } else {
            return $this->loginByUsername();
        }

    }

    /**
     * 通过用户名登录
     * @return array|bool
     */
    private function loginByEmail()
    {
        $member_model = new MemberModel();

        if (!$member_model->findByEmail($this->username)) {
            $this->error = '用户不存在';
            return false;
        }

        if (!$member_model->confirmPwd($this->password)) {
            $this->error = '密码错误';
            return false;
        }

        if ($member_model->status != 1) {
            $this->error = '帐号已禁用';
            return false;
        }

        $this->user = $member_model->getInfo();
        $this->storeStatus();
        return $this->user;
    }

    /**
     * 缓存登录信息
     */
    private function storeStatus()
    {
        $user_info = $this->user;
        if ($user_info['role_id'] == 1) {
            $user_info['is_supper'] = 1;
        }

        foreach ($user_info['permissions_list'] as  $permission){
            $permissions[] = strtolower($permission['path']);
        }

        session_start();
        session('user_id', $user_info['user_id']);
        session('permissions', $permissions);
        session('user_info', $user_info);
    }

    /**
     * 通过用户名登录
     * @return array|bool
     */
    private function loginByUsername()
    {
        $member_model = new MemberModel();
        if (!$this->user = $member_model->findByUsername($this->username)) {
            $this->error = '用户不存在';
            return false;
        }

        if (!$member_model->confirmPwd($this->password)) {
            $this->error = '密码错误';
            return false;
        }

        $this->storeStatus();
        $field = ['password', 'salt', 'del_flag'];
        $result = array_diff_key($this->user, array_flip($field));
        return $result;
    }

    /**
     * 获取登录用户菜单
     * @param $permissions
     * @return array|bool
     */
    public function getMenu($permissions)
    {
        //获取用户菜单列表
        $menu_list = [];
        $menu_model = new MenuModel();
        if ($menus = $menu_model->getList()) {
            foreach ($menus as $menu) {
                if ($menu['permissions'] > 0 && !in_array($menu['permissions'], $permissions)) {
                    continue;
                }
                $menu_list[] = $menu;
            }
        }

        $result = [];
        //过滤无子菜单的菜单组
        if (is_array($menu_list) && !empty($menu_list)) {
            $menu_list = $menu_model->getSortList($menu_list);
            foreach ($menu_list as $menu){
                if($menu['pid'] == 0 && $menu['permissions'] == 0 && empty($menu['son'])){
                    continue;
                }

                $result[] = $menu;
            }
        }

        return $result;
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        $this->destroyStatus();
    }

    /**
     * 退出登录
     */
    private function destroyStatus()
    {
        session_destroy();
    }


}

?>