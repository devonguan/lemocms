<?php
/**
 * lemocms
 * ============================================================================
 * 版权所有 2018-2027 lemocms，并保留所有权利。
 * 网站地址: https://www.lemocms.com
 * ----------------------------------------------------------------------------
 * 采用最新Thinkphp6实现
 * ============================================================================
 * Author: yuege
 * Date: 2019/9/21
 */
namespace app\h5\controller;

use app\common\controller\Base;

class Index extends Base{

    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $iniApp = $this->request->InApp;
        if($iniApp !=='WeChat'){

            echo '<script>alert("请在微信浏览器内打开")</script>';exit();
        }
    }

    public function index(){



    }
}