<?php
namespace Admin\Controller;
use Common\Lib\Upimg;
use Common\Lib\UpimgClass;
use Think\Controller;
use Common\Controller\BaseController;
class NewsController extends BaseController {
    protected $news_type = array(
        '1'=>'头条配置',
        '2'=>'国资委资讯',
        '3'=>'公司培训',
    );

    public function index(){
        $m_news = new \Admin\Model\NewsModel();

        $where = array(
            'subsite_id'=>$this->subsite_id,
            'status'=>0
        );
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
           }
       }

        $this->assign('news_list',$data);
        $this->assign('page_arr',$page_arr);
        $this->assign('news_type',$this->news_type);
        $this->display();
    }


    /*
     * 新增新闻
     */
    public function addNews(){
        //当operation_type为1时执行更新操作，默认为0执行插入
        $m_news = new \Admin\Model\NewsModel();
        $op_type = $this->params['op_type'];
        if($op_type == 1){
            $id = $this->params['news_id'];
            $data = $m_news->getNewsInfo($id);
            $data['cover'] = getImageBaseUrl($data['cover']);
            $this->assign('data',$data);
        }
        $this->assign('op_type',$op_type);
        $this->assign('news_type',$this->news_type);
        $this->display();
    }


    /*
     * 新增新闻动作
     */
    public function doAddNews(){
        $op_type = $this->params['op_type'];
        $data['title'] = $this->params['title'];
        $data['content'] = $this->params['contents'];
        $data['cover'] = $this->uploadImg('news');
        $data['type'] = $this->params['type'];

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
     * 删除课程动作
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


    /**
     * @todo 图片上传
     */
    public function uploadImg($upload_dir) {

        //文件上传路径
        if(!is_dir($upload_dir)){
            mkdir($upload_dir);
        }

        if(!is_writeable($upload_dir)) {
            $this->showMsg("上传目录不可写");
        }


        $upimgObj = new Upimg($_FILES['uploadFile']);
        if ($upimgObj->Save($upload_dir,false)) {
            $imgUrl = $upimgObj->GetSavePath();
           return $imgUrl;
        } else {
           return false;
        }
    }



}