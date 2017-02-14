<?php
namespace Admin\Controller;
use Common\Lib\UpimgClass;
use Think\Controller;
use Common\Controller\BaseController;
class SubsiteController extends BaseController {

    public function index(){
        $m_sub = new \Admin\Model\SubsiteModel();
        //当分站管理员访问时只能查看所属分站的信息
        if($this->su_type == C('SUBSITE_USER')){
            $where['subsite_id'] = $this->subsite_id;
        }

        $where['status'] = 0;

        if($name = $this->params['name']){
            $where['name'] = array('like','%'.$name.'%');
        }
        $data = array();

       if($count = $m_sub->getSubsiteCount($where)){
           //获取分页总数进一取整
           $page_arr = listPage($count,$this->limit);

           $data = $m_sub->getSubsiteList($where,$this->offset,$this->limit);

           foreach($data as $k=>$v){
               //处理banner
               $data[$k]['subsite_banner'] = getImageBaseUrl($v['subsite_banner']);

               //分站类型
               $sub_type = C('sub_type');
               $data[$k]['subsite_type'] = $sub_type[$v['subsite_type']];


           }
           $this->assign('page_arr',$page_arr);
       }

        $this->assign('sub_list',$data);

        $this->display();
    }


    /*
     * 新增/修改分站信息
     */
    public function addSubsite(){
        //当operation_type为1时执行更新操作，默认为0执行插入
        $m_sub= new \Admin\Model\SubsiteModel();
        $op_type = $this->params['op_type'];
        if($op_type == 1){
            $id = $this->params['subsite_id'];
            $data = $m_sub->getSubsiteInfo($id);
            $data['subsite_banner'] = getImageBaseUrl($data['subsite_banner']);

            $this->assign('data',$data);
        }


        $ethnic = C('ETHNIC_LIST');

        $this->assign('op_type',$op_type);
        $this->assign('ethnic_list',$ethnic);
        $this->display();
    }


    /*
     * 新增/修改分站动作
     */
    public function doAddSubsite(){

        $op_type = $this->params['op_type'];

        $data['name'] = $this->params['name'];
        if($this->params['subsite_banner']){
            $data['subsite_banner'] = $this->params['subsite_banner'];
        }

        $data['subsite_type'] = $this->params['type'];
        $data['address'] = $this->params['address'];
        $data['ename'] = $this->params['ename'];
        $data['intro'] = $this->params['intro'];
        $data['site_url'] = $this->params['site_url'];

        $m_subsite = new \Admin\Model\SubsiteModel();

        if($op_type == 1){
            $subsite_id = $this->params['subsite_id'];
            $where = array('subsite_id'=>$subsite_id,'status'=>0);
            $data['mtime'] = date('Y-m-d H:i:s',time());
            $result = $m_subsite->updateSubsite(array_filter($data),$where);
        }else{
            if($m_subsite->checkName($data)){
                $this->showMsg('该分站已存在');
            }
            $data['ctime'] = date('Y-m-d H:i:s',time());
            $result = $m_subsite->addSubsite($data);
        }

        if($result!== false){
            $this->showMsg('操作成功','index',1);
        }else{
            $this->showMsg('操作失败，请重试');
        }
    }



    /*
     * 删除学员动作
     */
    public function delSubsite(){
        $where['subsite_id'] = $this->params['subsite_id'];
        $m_student = new \Admin\Model\SubsiteModel();
        if($m_student->delSubsite($where) !== false){
            $this->showMsg('删除成功','index',1);
        }else{
            $this->showMsg('删除失败，请重试');
        }
    }





}