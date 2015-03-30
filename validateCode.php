<?php
/**
 * author: skyling<frenlee@163.com>
 * Class ValidateCode
 * 验证码类
 */
session_start();//开启session支持
class ValidateCode{
    private $charset = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789';//随机因子
    private $code;//验证码
    private $codelen = 4; //验证码长度
    private $width = 130;//宽度
    private $height = 50;//高度
    private $img;//图像资源句柄
    private $font;//字体
    private $fontsize=40;//字体大小
    private $fontclolor;//字体颜色
    private $session_name;

    /**
     * 构造函数
     */
    function __construct($session_name){
        $this->font = 'C:\windows\Fonts\TIMES.TTF';//字体绝对路径
        $this->session_name = $session_name;
    }

    /**
     * 生成验证码
     */
    private function createCode(){
        $_len = strlen($this->charset)-1;
        for($i=0;$i<$this->codelen;$i++){
            $this->code .= $this->charset[mt_rand(0, $_len-1)];
        }
        $_SESSION[$this->session_name] = $this->code;
    }

    /**
     * 创建背景
     */
    private function createBg(){
        $this->img = imagecreatetruecolor($this->width, $this->height);//创建图片
        $color = imagecolorallocate($this->img, mt_rand(157,255), mt_rand(157, 255), mt_rand(157, 255));//生成颜色
        imagefilledrectangle($this->img, 0, $this->height, $this->width, 0 , $color);//填充背景
    }

    /**
     * 生成文字
     */
    private function createFont(){
        $_x = $this->width / $this->codelen;
        for($i=0; $i<$this->codelen; $i++){
            $this->fontclolor = imagecolorallocate($this->img, mt_rand(0,156), mt_rand(0,156), mt_rand(0,156));
            if(file_exists($this->font)){
                imagettftext($this->img, $this->fontsize, mt_rand(-30,30), $_x*$i+mt_rand(1,5), $this->height / 1.4, $this->fontclolor, $this->font, $this->code[$i]);
            }else{
                imagestring($this->img, $this->fontsize,$_x*$i+mt_rand(1,5), $this->height/3 , $this->code[$i], $this->fontclolor );
            }
        }
    }
    /**
     * 生成干扰元素
     */
    private function createLine(){
        //线条
        for($i = 0; $i<mt_rand(4,6); $i++){
            $color = imagecolorallocate($this->img, mt_rand(0,156), mt_rand(0,156), mt_rand(0,156));
            imageline($this->img, mt_rand(0, $this->width), mt_rand(0, $this->height),mt_rand(0, $this->width), mt_rand(0, $this->height),$color);
        }
        //雪花
        for($i = 0; $i<100;$i++){
            $color = imagecolorallocate($this->img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
            imagestring($this->img, mt_rand(1,5), mt_rand(0, $this->width), mt_rand(0, $this->height), "*", $color);
        }
    }
    /**
     * 输出
     */
    private function outPut(){
        header('Content-type:image/png');
        imagepng($this->img);
        imagedestroy($this->img);
    }
    /**
     * 生成验证码
     */
    public function doimg(){
        $this->createBg();
        $this->createCode();
        $this->createLine();
        $this->createFont();
        $this->outPut();
    }

    /**
     * 获取验证码
     * @return string
     */
    public function getCode(){
        return strtolower($this->code);
    }
}

?>