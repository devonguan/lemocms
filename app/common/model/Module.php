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
 * Date: 2019/10/9
 */
namespace app\common\model;
use app\admin\controller\Base;
use lemo\helper\FileHelper;
use lemo\helper\StringHelper;
use think\facade\Config;
use think\facade\Request;
use think\facade\Db;
use think\facade\View;

class Module extends Common {
    public $title = '/**
  * lemocms
* ============================================================================
* 版权所有 2018-2027 lemocms，并保留所有权利。
* 网站地址: https://www.lemocms.com
* ----------------------------------------------------------------------------
* 采用最新Thinkphp6实现
* ============================================================================
* Author: yuege
*/';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    //加表
    public static function addTable($tablename,$prefix,$moduleid,$emptytable=1){

        if ($emptytable == 1) {
//            文章模型

            $sql = <<<EOF
				CREATE TABLE `{$tablename}` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
                  `column_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '栏目id',
                  `title` varchar(120) NOT NULL DEFAULT '' COMMENT '标题',
                  `title_style` varchar(225) NOT NULL DEFAULT '' COMMENT '标题样式',
                  `thumb` varchar(225) NOT NULL DEFAULT '' COMMENT '缩略图',
                  `keywords` varchar(120) NOT NULL DEFAULT '' COMMENT '关键词',
                  `description` mediumtext NOT NULL COMMENT '描述',
                  `tags` varchar(255) NOT NULL COMMENT '标签',
                  `content` mediumtext NOT NULL  COMMENT '内容',
                  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
                  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
                  `hits` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '点击',
                  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
                  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
                  PRIMARY KEY (`id`),
                  KEY `status` (`id`,`status`,`sort`),
                  KEY `column_id` (`id`,`column_id`,`status`),
                  KEY `sort` (`id`,`column_id`,`status`,`sort`)
                ) ENGINE=InnoDb  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='{$tablename}模型表';
EOF;
            Db::execute($sql);

            Db::execute("INSERT INTO `" . $prefix . "field` (`moduleid`,`field`,`name`,`required`,`maxlength`,`rule`,`msg`,`type`,`option`,`value`,`sort`,`status`,`create_time`) VALUES ( '" . $moduleid . "', 'column_id', '栏目','1', '6', '', '必须选择一个栏目', 'column_id','', '','1', '1', '".time()."' )");
            Db::execute("INSERT INTO `" . $prefix . "field` (`moduleid`,`field`,`name`,`required`,`maxlength`,`rule`,`msg`,`type`,`option`,`value`,`sort`,`status`,`create_time`) VALUES ( '" . $moduleid . "', 'title', '标题', '1', '180', '', '标题必须为1-180个字符','text','', '','2',  '1', '".time()."' )");
            Db::execute("INSERT INTO `" . $prefix . "field` (`moduleid`,`field`,`name`,`required`,`maxlength`,`rule`,`msg`,`type`,`option`,`value`,`sort`,`status`,`create_time`) VALUES ( '" . $moduleid . "', 'keywords', '关键词', '1', '80', '',  '', 'text', '','','3', '1', '".time()."' )");
            Db::execute("INSERT INTO `" . $prefix . "field` (`moduleid`,`field`,`name`,`required`,`maxlength`,`rule`,`msg`,`type`,`option`,`value`,`sort`,`status`,`create_time`) VALUES ( '" . $moduleid . "', 'description', 'SEO简介', '1',  '0', '', '', 'textarea','', '','4', '1', '".time()."')");
            Db::execute("INSERT INTO `" . $prefix . "field` (`moduleid`,`field`,`name`,`required`,`maxlength`,`rule`,`msg`,`type`,`option`,`value`,`sort`,`status`,`create_time`) VALUES ( '" . $moduleid . "', 'tags', '标签', '0', '0', '', '', 'text', '','','5', '1', '1' )");
            Db::execute("INSERT INTO `" . $prefix . "field` (`moduleid`,`field`,`name`,`required`,`maxlength`,`rule`,`msg`,`type`,`option`,`value`,`sort`,`status`,`create_time`) VALUES ( '" . $moduleid . "', 'content', '内容',  '0',  '0',  '', '', 'editor','0|ueditor','ueditor','6', '1', '".time()."')");
            Db::execute("INSERT INTO `" . $prefix . "field` (`moduleid`,`field`,`name`,`required`,`maxlength`,`rule`,`msg`,`type`,`option`,`value`,`sort`,`status`,`create_time`) VALUES ( '" . $moduleid . "', 'create_time', '添加时间',  '0',  '11', '',  '','datetime', '','7','1', '".time()."'  )");
            Db::execute("INSERT INTO `" . $prefix . "field` (`moduleid`,`field`,`name`,`required`,`maxlength`,`rule`,`msg`,`type`,`option`,`value`,`sort`,`status`,`create_time`) VALUES ( '" . $moduleid . "', 'update_time', '更新时间', '0', '11', '',  '','datetime', '','','8','1', '".time()."'  )");
            Db::execute("INSERT INTO `" . $prefix . "field` (`moduleid`,`field`,`name`,`required`,`maxlength`,`rule`,`msg`,`type`,`option`,`value`,`sort`,`status`,`create_time`) VALUES ( '" . $moduleid . "', 'status', '状态', '1',  '1',  '', '', 'radio', '0|禁用,1|启用','1','9','1', '".time()."' )");
            Db::execute("INSERT INTO `" . $prefix . "field` (`moduleid`,`field`,`name`,`required`,`maxlength`,`rule`,`msg`,`type`,`option`,`value`,`sort`,`status`,`create_time`) VALUES ( '" . $moduleid . "', 'hits', '点击次数',  '0',  '8',  '', '', 'number', '','','10', '1', '".time()."' )");

        } else {
            $sql = <<<EOF
            CREATE TABLE `{$tablename}`  (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
			  `column_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
			  `uid` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
			  `username` varchar(40) NOT NULL DEFAULT '' COMMENT '用户名',
			  `title` varchar(120) NOT NULL DEFAULT '' COMMENT '标题',
			  `title_style` varchar(225) NOT NULL DEFAULT '' COMMENT '标题样式',
			  `thumb` varchar(225) NOT NULL DEFAULT '' COMMENT '缩略图',
			  `keywords` varchar(120) NOT NULL DEFAULT '' COMMENT '关键词',
			  `description` mediumtext NOT NULL COMMENT '描述',
              `tags` varchar(255) NOT NULL COMMENT '标签',
			  `content` mediumtext NOT NULL  COMMENT '内容',
			  `posid` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '推荐位',
			  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
			  `recommend` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '允许评论',
			  `visite` varchar(100) NOT NULL DEFAULT '' COMMENT '访问权限',
			  `is_read` smallint(5) NOT NULL DEFAULT '0' COMMENT '是否可阅读',
			  `readpoint` smallint(5) NOT NULL DEFAULT '0' COMMENT '阅读收费',
			  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
			  `hits` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '点击',
			  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
			  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
			  PRIMARY KEY (`id`),
			  KEY `status` (`id`,`status`,`sort`),
			  KEY `column_id` (`id`,`column_id`,`status`),
			  KEY `sort` (`id`,`column_id`,`status`,`sort`)
                ) ENGINE=InnoDb  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='{$tablename}模型表';
EOF;

            Db::execute($sql);
            Db::execute("INSERT INTO `" . $prefix . "field` (`moduleid`,`field`,`name`,`required`,`maxlength`,`rule`,`msg`,`type`,`option`,`value`,`sort`,`status`,`create_time`) VALUES ( '" . $moduleid . "', 'column_id', '栏目',  '1',  '6', '', '必须选择一个栏目', 'column_id', '','','1','1', '".time()."' )");
            Db::execute("INSERT INTO `" . $prefix . "field` (`moduleid`,`field`,`name`,`required`,`maxlength`,`rule`,`msg`,`type`,`option`,`value`,`sort`,`status`,`create_time`) VALUES ( '" . $moduleid . "', 'title', '标题', '1',  '80', '', '标题必须为1-80个字符',  'text', '','','2', '1', '".time()."' )");
            Db::execute("INSERT INTO `" . $prefix . "field` (`moduleid`,`field`,`name`,`required`,`maxlength`,`rule`,`msg`,`type`,`option`,`value`,`sort`,`status`,`create_time`) VALUES ( '" . $moduleid . "', 'keywords', '关键词','1',  '200', '','关键词必须在0-200个内',  'text','', '','3', '1', '".time()."')");
            Db::execute("INSERT INTO `" . $prefix . "field` (`moduleid`,`field`,`name`,`required`,`maxlength`,`rule`,`msg`,`type`,`option`,`value`,`sort`,`status`,`create_time`) VALUES ( '" . $moduleid . "', 'description', 'SEO简介', '1',  '0', '','',  'textarea', '','','4','1', '".time()."')");
            Db::execute("INSERT INTO `" . $prefix . "field` (`moduleid`,`field`,`name`,`required`,`maxlength`,`rule`,`msg`,`type`,`option`,`value`,`sort`,`status`,`create_time`) VALUES ( '" . $moduleid . "', 'content', '内容', '0', '255', '', '', 'editor','0|ueditor', 'ueditor','5', '1', '".time()."')");
            Db::execute("INSERT INTO `" . $prefix . "field` (`moduleid`,`field`,`name`,`required`,`maxlength`,`rule`,`msg`,`type`,`option`,`value`,`sort`,`status`,`create_time`) VALUES ( '" . $moduleid . "', 'create_time', '创建时间',  '1',  '11', '','','datetime','', '', '6','1', '".time()."')");
            Db::execute("INSERT INTO `" . $prefix . "field` (`moduleid`,`field`,`name`,`required`,`maxlength`,`rule`,`msg`,`type`,`option`,`value`,`sort`,`status`,`create_time`) VALUES ( '" . $moduleid . "', 'update_time', '更新时间',  '1', '11','', '','datetime', '','', '6','1', '".time()."')");
            Db::execute("INSERT INTO `" . $prefix . "field` (`moduleid`,`field`,`name`,`required`,`maxlength`,`rule`,`msg`,`type`,`option`,`value`,`sort`,`status`,`create_time`) VALUES ( '" . $moduleid . "', 'status', '状态',  '1', '1', '', '', 'radio', '0|未发布,1|发布','1','7','1', '".time()."')");
            Db::execute("INSERT INTO `" . $prefix . "field` (`moduleid`,`field`,`name`,`required`,`maxlength`,`rule`,`msg`,`type`,`option`,`value`,`sort`,`status`,`create_time`) VALUES ( '" . $moduleid . "', 'recommend', '允许评论',  '0', '1','', '', 'radio', '0|禁止评论,1|允许评论','1','8', '1', '".time()."')");
            Db::execute("INSERT INTO `" . $prefix . "field` (`moduleid`,`field`,`name`,`required`,`maxlength`,`rule`,`msg`,`type`,`option`,`value`,`sort`,`status`,`create_time`) VALUES ( '" . $moduleid . "', 'readpoint', '阅读收费', '0',  '5', '','', 'number','', '0','9', '1', '".time()."' )");
            Db::execute("INSERT INTO `" . $prefix . "field` (`moduleid`,`field`,`name`,`required`,`maxlength`,`rule`,`msg`,`type`,`option`,`value`,`sort`,`status`,`create_time`) VALUES ( '" . $moduleid . "', 'is_read', '是否可阅读', '0',  '1','', '', 'radio', '0|禁止,1|允许','1','9', '1','".time()."')");
            Db::execute("INSERT INTO `" . $prefix . "field` (`moduleid`,`field`,`name`,`required`,`maxlength`,`rule`,`msg`,`type`,`option`,`value`,`sort`,`status`,`create_time`) VALUES ( '" . $moduleid . "', 'hits', '点击次数', '0',  '8',  '','', 'number', '','1','10','1', '".time()."' )");
            Db::execute("INSERT INTO `" . $prefix . "field` (`moduleid`,`field`,`name`,`required`,`maxlength`,`rule`,`msg`,`type`,`option`,`value`,`sort`,`status`,`create_time`) VALUES ( '" . $moduleid . "', 'visite', '访问权限', '0', '1', '', '', 'radio', '0|开启,1|关闭','1','11','1', '".time()."')");
            Db::execute("INSERT INTO `" . $prefix . "field` (`moduleid`,`field`,`name`,`required`,`maxlength`,`rule`,`msg`,`type`,`option`,`value`,`sort`,`status`,`create_time`) VALUES ( '" . $moduleid . "', 'posid', '推荐位',  '0','1', '','', 'posid','', '','12','12', '".time()."')");
            Db::execute("INSERT INTO `" . $prefix . "field` (`moduleid`,`field`,`name`,`required`,`maxlength`,`rule`,`msg`,`type`,`option`,`value`,`sort`,`status`,`create_time`) VALUES ( '" . $moduleid . "', 'tags', '标签',  '0', '255', '', '', 'text','', '','14','1','".time()."')");
        }
        return true;
    }

    //添加模型
    public static function addModel($table){
        $modelName = StringHelper::formatClass($table);
        $file = app()->getRootPath().'view/admin/template/model.html';
        $content = file_get_contents($file);
        $content = str_replace('$table',$modelName,$content);
        $modelFile = app()->getRootPath()."app/common/model/".$modelName.'.php';
        FileHelper::createFile($modelFile,$content);
    }
    public static function delModel($table){


    }
    //添加控制器
    public static function addController($table){
        $controllerName = StringHelper::formatClass($table);
        $file = app()->getRootPath().'view/admin/template/controller.html';
        $content = file_get_contents($file);
        $content = str_replace('$table',$controllerName,$content);
        $controllerFile = app()->getRootPath()."app/admin/controller/".$controllerName.'.php';
        FileHelper::createFile($controllerFile,$content);
        return true;
    }
   
    public static function delController($table){
        $controllerName = StringHelper::formatClass($table);

    }
    //添加视图
    public static function addView($table){
        $view = app()->getRootPath().'view/admin/'.$table.'/add.html';
        $view1 = app()->getRootPath().'view/admin/'.$table.'/index.html';
        $source = app()->getRootPath().'view/admin/template/add.html';
        $source1 = app()->getRootPath().'view/admin/template/index.html';
        FileHelper::copyDir($source,$view);
        FileHelper::copyDir($source1,$view1);
        return true;

    }

    public static function delView($table){

    }

}