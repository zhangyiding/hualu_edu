<?php
namespace Admin\Controller;
use Common\Lib\Upimg;
use Common\Lib\UpimgClass;
use Common\Model\BaseModel;
use Think\Controller;
use Common\Controller\BaseController;
class NewsController extends BaseController {

    public function index(){
        $m_news = new \Admin\Model\NewsModel();
        $m_base = new BaseModel();

        //当分站管理员访问时只能查看所属分站的新闻
        if($this->su_type = C('SUBSITE_USER')){
            $where['subsite_id'] = $this->subsite_id;
        }

        $where['status'] = 0;

        $data = array();
        $page_arr = array();
       if($count = $m_news->getNewsCount($where)){
           //获取分页总数进一取整
           $page_count = ceil($count/$this->limit);
           for($i=1;$i<=$page_count;$i++){
               $page_arr[] = $i;
           }

           $data = $m_news->getNewsList($where,$this->offset,$this->limit);
           foreach($data as $k=>$v){
               $data[$k]['cover'] = getImageBaseUrl($v['cover']);

               //获取分站信息
               $subsite_info = $m_base->getSubsiteInfo($v['subsite_id']);
               $data[$k]['subsite_name'] = $subsite_info['name'];
           }
       }

        $this->assign('news_list',$data);
        $this->assign('page_arr',$page_arr);
        $this->assign('news_type',C('news_type_admin'));
        $this->display();
    }


    /*
     * 新增新闻
     */
    public function addNews(){
        //当operation_type为1时执行更新操作，默认为0执行插入
        $m_news = new \Admin\Model\NewsModel();
        $op_type = $this->params['op_type'];

        //当分站管理员访问时只能查看所属分站的新闻
        if($this->su_type = C('SUBSITE_USER')){
            $new_type = C('news_type_admin_sub');
        }else{
            $new_type = C('news_type_admin');
        }

        if($op_type == 1){
            $id = $this->params['news_id'];
            $data = $m_news->getNewsInfo($id);
            $data['cover'] = getImageBaseUrl($data['cover']);
            $this->assign('data',$data);
        }

        $this->assign('op_type',$op_type);
        $this->assign('news_type',$new_type);
        $this->display();
    }


    /*
     * 新增新闻动作
     */
    public function doAddNews(){
        $op_type = $this->params['op_type'];
        $data['title'] = $this->params['title'];
        $data['content'] = $this->params['contents'];
        $data['cover'] = uploadFile('news');
        $data['type'] = $this->params['type'];
        $data['title_format'] = $this->params['title_format'];

        $data['subsite_id'] = $this->subsite_id;
        $data['creator'] = $this->creator;


        $m_news = new \Admin\Model\NewsModel();

        if($op_type == 1){
            $news_id = $this->params['news_id'];
            $where = array('news_id'=>$news_id,'status'=>0);
            $data['mtime'] = date('Y-m-d H:i:s',time());
            $result = $m_news->updataNews($data,$where);
        }else{
            $data['ctime'] = date('Y-m-d H:i:s',time());
            $result = $m_news->doAddNews($data);

        }

        if($result!== false){
            $this->showMsg('添加成功','index',1);
        }else{
            $this->showMsg('添加失败，请重试');
        }
    }

    /*
     * 删除新闻动作
     */
    public function delNews(){
        $where['news_id'] = $this->params['news_id'];
        $m_news = new \Admin\Model\NewsModel();
        if($m_news->delNews($where) !== false){
            $this->showMsg('删除成功','index',1);
        }else{
            $this->showMsg('删除失败，请重试');
        }
    }





}