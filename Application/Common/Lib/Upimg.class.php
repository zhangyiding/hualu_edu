<?php

/**-----------------------------------
 * TODO : 图片上传接口
 *-----------------------------------*/
namespace Common\Lib;


class Upimg {
    public $errmsg="OK";   //默认返回信息；
    private $mode=1;        //默认保存类型,0 : 保存一般文件 ， 1 ：保存图片；
    private $files;       //默认上传文件变量；
    private $filename;      //指定文件的文件名；
    private $twidth=120;    //默认普通缩略图宽度；
    private $theight=120;   //默认普通缩略图高度；
    private $btwidth=500;   //默认大缩略图宽度；
    private $btheight=500;  //默认大缩略图高度；
    private $maxfilesize=2097152; //默认上传文件最大值：2M；
    private $fileformat="jpeg,gif,bmp,jpg,png"; 
    private $outpath="";    //默认原图保存路径；
    private $outthumbpath="";   //默认普通缩略图保存路径；
    private $outbigthumbpath="";    //默认大缩略图保存路径；
    private $fileTypeArr = array('image/gif','image/x-png','image/png','image/jpg','image/pjpeg','image/jpeg'); //默认允许的图片种类；

    /**
    public function UpimgClass($files)
    {
        $this->__construct($files);
    }
    */

    public function __construct($files)
    {
        define("IMG_DIR" , 'public/admin/image');
        define("FILE_DIR" , ".");
        define("ORIG_IMG" , "img");
        define("COMM_THUMB_SIZE" , "120");
        define("BIG_THUMB_SIZE" , "500");
        if(!defined('IMG_URL'))
            define("IMG_URL" , "http://img.hualu.com");
        $this->files = $files;
    }

    public function __destruct()
    {
    
    }

    /**
     *  TODO：设置$_FILES["file"]变量；
     *  param : $files = $_FILES["file"];
     */
    public function SetFilesParam($files)
    {
        $this->files = $files; 
    }

    /*
     *  param：$mode 图片保存模式 
     *
     *  0 、保存一般文件 ； 
     *  1 、保存图片； 
     */
    public function SetSaveMod($mode=1)
    {
        $this->mode = $mode;
    }

    /**
     *  TODO 设置希望保存的文件名
     *  param $filename 文件名；
     */
    public function SetFileName($filename)
    {
        $this->filename = $filename;
    }

    /**
     *  TODO 设置允许上传的文件上限；
     *  param $filesize 文件大小 ， 单位M；
     */
    public function SetMaxFileSize($filesize)
    {
        $this->maxfilesize = $filesize * 1048576;
    }

    /**
     *  TODO 设置普通缩略图的保存尺寸；
     *  param : $width 宽度 , $height 高度;
     **/
    public function SetThumbSize($width=120,$height=120)
    {
        $this->twidth = $width;
        $this->theight = $height;
    }

    /**
     *  TODO 设置大缩略图的保存尺寸；
     *  param : $width 宽度 , $height 高度;
     */
    public function SetBigThumbSize($width=500,$height=500)
    {
        $this->btwidth = $width;
        $this->btheight = $height;
    }

    /**
 *  TODO 获取图片/文件上传的路径名，遵循相应的命名规则；
 */
    public function GetSavePath()
    {
        if($this->mode){
            $outpath = str_replace(IMG_DIR."/" , "" , $this->outpath);
        }else{
            $outpath = str_replace(FILE_DIR."/" , "" , $this->outpath);
        }
        return $outpath;
    }

    /**
     *  TODO 获取上传图片大缩略图相对路径，遵循相应的命名规则；
     */
    public function GetBigthumbpathPath()
    {
        if($this->mode){
            $outpath = str_replace(IMG_DIR."/" , "" , $this->outbigthumbpath);
        }else{
            $outpath = str_replace(FILE_DIR."/" , "" , $this->outbigthumbpath);
        }
        return $outpath;
    }


    /**
     *  TODO : 用户上传接口
     *  param：$savepath 图片存储路径，缩略图遵循一定的存储规范；
     *         $mksubdir 是否创建该路径下的子目录， 
     *             true  默认值， 创建,命名规则：(hostname_date)主机名_日期的后六位，比如：test1_090617,
     *             false 不创建，使用现有文件夹；
     */
    public function Save($savepath,$mksubdir=false)
    {
        //保存图片；
        if($this->mode){
            $savepath = IMG_DIR.'/'.$savepath;

            $files = $this->files;
            if(!$this->CheckImg($files)){
                return false;
            }

            //生成路径；
            $savepath = $this->makeDirectory($savepath , $mksubdir);
            $knamearray = explode("." , $files["name"]);
            $kname = strtolower($knamearray[count($knamearray)-1]);
            $fileName = $this->GenFileName($kname);

            //保存原图；
            if(!$this->SaveOrigImg($this->files , $savepath."/".ORIG_IMG."/".$fileName)){
                return false;
            }
            //保存普通缩略图；
            if(!$this->SaveThumImg($this->files , $savepath."/".COMM_THUMB_SIZE."/".$fileName, $kname)){
                return false;
            }
            //保存大缩略图；
            if(!$this->SaveBigThumImg($this->files , $savepath."/".BIG_THUMB_SIZE."/".$fileName, $kname)){
                return false;
            }
            if(file_exists($files["tmp_name"])){
                if(!unlink($files["tmp_name"])){
                    $this->errmsg = "系统错误!";
                    return false;
                }
            }
        }else{  //保存一般性文件
            $savepath = FILE_DIR."/".$savepath;
            $files = $this->files;
            if(!$this->CheckFiles($files)){
                return false;
            }
            //生成路径；
            $savepath = $this->makeDirectory($savepath , $mksubdir);

            $knamearray = explode("." , $files["name"]);
            $kname = strtolower($knamearray[count($knamearray)-1]);
            $fileName = $this->GenFileName($kname);

            //保存原图；
            if(!$this->SaveFile($this->files , $savepath."/".$fileName)){
                return false;
            }
        }

        return true;
    }

    /**
     * TODO 只保留原图；
     * param $files 上传文件参数 , $savepath 存储文件夹 , $mksubdir 是否在$savepath中建立子文件夹；
     */
    public function SaveOrigImg($files , $upFileName)
    {
        //储存原图片；
        if(!copy($files['tmp_name'],$upFileName)){
            $this->errmsg = "原图存储失败！";
            return false; 
        }

        $this->outpath = $upFileName;
        return true; 
    }

    //保存普通的缩略图；
    public function SaveThumImg($files , $upThumFile, $kname)
    {
        //储存缩略图；
        if(!$this->Resize($files , $upThumFile , $kname , $this->twidth , $this->theight)){
            return false;
        } 

        $this->outthumbpath = $upThumFile;
        return true;
    }

    //原图过大，保存大的缩略图；
    public function SaveBigThumImg($files , $upThumFile, $kname)
    {
        //储存大缩略图；
        if(!$this->Resize($files , $upThumFile , $kname , $this->btwidth , $this->btheight)){
            return false;
        } 

        $this->outbigthumbpath = $upThumFile;
        return true; 
    }

    /**
     * 上传一般文件，比如flash、文本等；
     * TODO 只保留原图；
     * param $files 上传文件参数 , $savepath 存储文件夹 , $mksubdir 是否在$savepath中建立子文件夹；
     */
    private function SaveFile($files , $upFileName)
    {
        //储存原图片；
        if(!move_uploaded_file($files['tmp_name'],$upFileName)){
            $this->errmsg = "文件存储失败！";
            return false; 
        }

        $this->outpath = $upFileName;
        return true; 
    }

    //对特殊文件类型进行检测；
    private function CheckFileTypes()
    {
    
    }

    //对常规图片类型进行检测；
    private function CheckImg($files)
    {
        //检查img文件是否正常；
        $tmpName = $files["tmp_name"];
        $size = $files["size"];
        $fileType = $files['type'];
        
        if(!$files){
            $this->errmsg = "没有上传文件";
            return false;
        }
        if(!file_exists($tmpName)){
            $this->errmsg = "上传不成功!";
            return false;
        } 
        if($size > $this->maxfilesize){
            $this->errmsg = "图片大小应小于".($this->maxfilesize/(1024*1024))."M";
            return false;
        }
        if(!in_array($fileType,$this->fileTypeArr))
        {
            $this->errmsg = "上传的图片类型只能为:png,jpeg,jpg,gif";
            return false;
        }
        return true;
    }

    //对一般文件进行验证；
    private function CheckFiles($files)
    {
        $size = $files["size"];
        if(!$files){
            $this->errmsg = "没有上传文件";
            return false;
        }
        if($size > $this->maxfilesize){
            $this->errmsg = "文件大小应小于".($this->maxfilesize/(1024*1024))."M";
            return false;
        }
        return true;
    }

    //对图片进行处理；
    private function Resize($files , $upThumFile , $kname , $width , $height)
    {
        // 取得上传图片
        if ($kname=="gif"){
            if(!$src = imagecreatefromgif($files['tmp_name'])){
                $this->errmsg = "gif图片获取失败！";
                return false;
            }
        }else if($kname == "png"){
            if(!$src = imagecreatefrompng($files['tmp_name'])){
                $this->errmsg = "png图片获取失败！";
                return false;
            }
        }else{
            if(!$src = imagecreatefromjpeg($files['tmp_name'])){
                $this->errmsg = "图片获取失败！";
                return false;
            }
        }

        //调整图片方向
        $exif = exif_read_data($files['tmp_name']);//获取exif信息
        if (isset($exif['Orientation']) && $exif['Orientation'] == 6) {
            //旋转
            $src = imagerotate($src,-90,0);
        }

        // 取得來源圖片長寬
        if(!$src_w = imagesx($src)){
            $this->errmsg = "图片尺寸获取失败！";
            return false;
        }
        if(!$src_h = imagesy($src)){
            $this->errmsg = "图片尺寸获取失败！";
            return false;
        }

        // 假設尺寸需要限制在$width , $height之间；
        if(($src_w <= $width) && ($src_h <= $height)){
            //按原图片尺寸储存；
            if(!copy($files['tmp_name'],$upThumFile)){
                $this->errmsg = "缩略图存储失败！";
                return false; 
            }else{
                return true;
            }
        }

        if($src_w > $src_h){
            $thumb_w = $width;
            $thumb_h = intval($src_h / $src_w * $thumb_w);
        }else{
            $thumb_h = $height;
            $thumb_w = intval($src_w / $src_h * $thumb_h);
        }

        // 建立縮圖
        if($kname == "png"){
            if(!$thumb = imagecreate($thumb_w, $thumb_h)){
                $this->errmsg = "缩略图创建失败！";
                return false;
            }
        }else{
            if(!$thumb = imagecreatetruecolor($thumb_w, $thumb_h)){
                $this->errmsg = "缩略图创建失败！";
                return false;
            }
        }

        // 開始縮圖
        if(!imagecopyresampled($thumb, $src, 0, 0, 0, 0, $thumb_w, $thumb_h, $src_w, $src_h)){
            $this->errmsg = "缩图失败！";
            return false;
        }
        
        // 儲存縮圖到指定 thumb 目錄
        if ($kname=="gif"){
            if(!imagegif($thumb, $upThumFile)){
                $this->errmsg = "gif缩放图片保存失败!";
                return false;
            }
        }else if($kname == "png"){
            if(!imagepng($thumb, $upThumFile)){
                $this->errmsg = "png缩放图片保存失败!";
                return false;
            }
        }else{
            if(!imagejpeg($thumb, $upThumFile)){
                $this->errmsg = "缩放图片保存失败!";
                return false;
            }
        }
        return true; 
    }

    /**
     *  TODO 生成随机文件名；
     **/
    private function GenFileName($kname)
    {
        //若用户没有指定用户名，生成随机文件名；
        if(!$filename = $this->filename){
            $filename = time()."_".rand(1000 , 9999); 
        }

        return $filename.".".$kname;
    }

    /**
     *  TODO 生成存储目录
     *  param $dirName 保存路径； $mksubdir 是否建立子文件夹；
     **/
    private function makeDirectory($dirName , $mksubdir) 
    {
        $dirName = str_replace("\\","/",$dirName);

        if($mksubdir){
            $hostname = $_SERVER["HOSTNAME"];
            if(empty($hostname)){
                $hostname = gethostbyaddr(gethostbyname($_SERVER["SERVER_NAME"]));
            }
            $pattern = "[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}";
            if(ereg($pattern , $hostname)){
                $hostname = substr($hostname , -3);
            }
            $savepath = $dirName."/".$hostname."_".date("ymd");
        }else{
            $savepath = $dirName."/".date('ymd');
        }

        if(!is_dir($savepath)){
            if(!mkdir($savepath, 0777,true)){
//                echo $savepath;
//                exit("不能建立目录22 $savepath");
                $this->errmsg = "不能建立目录:".$savepath;
                return false;
            }
//            $savePathArr = explode('/' , $savepath);
//            $num = count($savePathArr);
//            $temp = '';
//            for($i=0 ; $i < $num ; ++$i){
//                if($savePathArr[$i]){
//                    $temp .= "/".$savePathArr[$i];
//                }
//                if ($temp && !is_dir($temp)) {
//                    $oldmask = umask(0);
//                    if(!mkdir($temp, 0777)){
//											echo $temp;
//                        exit("不能建立目录 $temp");
//                    }
//                    umask($oldmask);
//                }
//            }
        }

        //上传一般文件；
        if(!$this->mode){
            return $savepath;
        }

        //上传图片，根据尺寸，建立三个子目录；
        $pathArr = array(
            ORIG_IMG, COMM_THUMB_SIZE, BIG_THUMB_SIZE
        );
        $temp = $savepath;
        $count = count($pathArr);
        $n = 0;
        for($n ; $n < $count ; ++$n){
            $savepath = $temp."/".$pathArr[$n]; 
            if(!is_dir($savepath)){
                $oldmask = umask(0);
                if(!mkdir($savepath , 0777)){
                    exit("不能建立目录 $savepath"); 
                }
                umask($oldmask);
            }
        }
        return $temp;
    }

    /**
     *  TODO : 获取相关缩略图地址；
     *  param $imgUrl 图片存储路径；
     *        $size 缩略图存储尺寸，对应存储规则:
     *        1 : 小缩略图；
     *        2 : 大缩略图；
     *        3 : 原图；
     **/
    public static function GetThumbImgUrl($imgUrl , $size=1)
    {
        //绝对路径；
        if((strpos($imgUrl , "http") === 0) || (strpos($imgUrl , "http") > 0)){
            return $imgUrl;
        }
        switch($size){ 
            case 1 : 
                $sizeStr = "/120/";
                break;
            case 2 : 
                $sizeStr = "/500/";
                break;
            case 3 : 
                $sizeStr = "/img/";
                break;
            default:
                $sizeStr = "/120/";
        }

        $imgUrl = str_replace("/img/" , $sizeStr , $imgUrl);
        return IMG_URL."/".$imgUrl;
    }

    /**
     *  TODO : 返回默认图片
     *  param $type 返回不同的图片
     */
    public static function GetDefaultImgUrl($type="blog")
    {
        switch($type){
            case "blog":
                $imgUrl = "blog/photo/none.gif";
                break;
            case "group":
                $imgUrl = "group/photo/group.gif";
                break;
            case "album":
                $imgUrl = "blog/photo/album.gif";
                break;
            default:
                $imgUrl = "blog/photo/none.gif";
        }
        return IMG_URL."/".$imgUrl;
    }
}

?>
