<?php
namespace Common\Lib;
use Think\Model;
use Common\Lib\Curl;
class UpVideo {

	/**
     * 获取视频文件总长度和创建时间
     * @param string $file 文件路径
     * @return array
     */
	public function getInfo($file,$unit='m')
    {

        if( $arw_size = filesize(iconv('UTF-8','GB2312',$file))){
            $vtime = exec("ffmpeg -i " . $file . " 2>&1 | grep 'Duration' | cut -d ' ' -f 4 | sed s/,//");//总长度
            $ctime = date("Y-m-d H:i:s", filectime($file));//创建时间
            $duration = explode(":", $vtime);
            $duration_in_seconds = $duration[0] * 3600 + $duration[1] * 60 + round($duration[2]);//转化为秒

            //获取视频文件大小
            $unit = (empty($unit)) ? 'm' : $unit; //默认单位为m
            $arw_size = filesize($file);
            if ($arw_size) {
                switch ($unit) {
                    case 'b':
                        $size = $arw_size;
                        break;
                    case 'kb':
                        $size = round($arw_size / 1000);
                        break;
                    case 'm':
                        $size = round($arw_size / 1000 / 1000);
                        break;
                    default:
                        $size = round($arw_size / 1000);
                }


                //获取视频名称以及扩展
                $v_info = pathinfo(iconv('UTF-8','GB2312',$file));

                return array('vtime' => $vtime,
                    'ctime' => $ctime,
                    'duration' => $duration_in_seconds,
                    'size' => $size,
                    'name' => $v_info['filename'],
                    'ext'=>$v_info['extension']
                );
        }
        }else{
            return false;
        }
    }


    /**
     * 获取视频文件缩略图
     * @param string $file 文件路径
     * @return array
     */
    public function getVideoCover($file,$time) {
        if(empty($time))$time = '1';//默认截取第一秒第一帧
        $strlen = strlen($file);
        $videoCover = substr($file,0,$strlen-4);
        $videoCoverName = $videoCover.'.jpg';//缩略图命名
        exec("ffmpeg -i ".$file." -y -f mjpeg -ss ".$time." -t 0.001 -s 320x240 ".$videoCoverName."",$out,$status);
        if($status == 0)return $videoCoverName;
        elseif ($status == 1)return FALSE;
    }









}




?>