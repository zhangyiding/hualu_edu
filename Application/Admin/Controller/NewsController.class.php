<?php
namespace Admin\Controller;
use Common\Lib\Upimg;
use Common\Lib\UpimgClass;
use Think\Controller;
use Common\Controller\BaseController;
class NewsController extends BaseController {
    public function index(){

        $m_news = new \Admin\Model\NewsModel();
        $news_type = array(
            '1'=>'集团新闻',
            '2'=>'行业新闻',
            '3'=>'学院新闻',
        );

        $data = $m_news->getNewsList();
        foreach($data as $k=>$v){
            $data[$k]['img'] = empty($v['img'])? C('IMG_URL').'/'.'news/no_img.jpg': C('IMG_URL') . '/'.$v['img'];
        }
        $this->assign('news_list',$data);
        $this->assign('news_type',$news_type);

        $this->display();
    }


    /*
     * 新增新闻
     */
    public function addNews(){
        //当type为1时执行更新操作，为2时插入操作
        $m_news = new \Admin\Model\NewsModel();
        $type = $this->params['type'];
        if($type == 1){
            $id = $this->params['id'];
            $data = $m_news->getNewsInfo($id);
            $this->assign('data',$data);
        }
        $news_type = array(
            '1'=>'集团新闻',
            '2'=>'行业新闻',
            '3'=>'学院新闻',
        );

        $this->assign('news_type',$news_type);

        $this->display();
    }


    /*
     * 新增课程动作
     */
    public function doAddNews(){
        $img_path = $this->uploadImg('news');
        $where['tittle'] = $this->params['tittle'];
        $where['content'] = $this->params['contents'];
        $where['add_time'] = time();
        $where['img'] = $img_path;
        $where['is_recommend'] = $this->params['is_recommend'];
        $where['type'] = $this->params['type'];
        $m_coures = new \Admin\Model\NewsModel();
        if($m_coures->doAddNews($where) !== false){
            $this->showMsg('添加成功','index',1);
        }else{
            $this->showMsg('添加失败，请重试');
        }
    }

    /*
     * 删除课程动作
     */
    public function delNews(){
        $where['id'] = $this->params['id'];
        $m_coures = new \Admin\Model\NewsModel();
        if($m_coures->delNews($where) !== false){
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