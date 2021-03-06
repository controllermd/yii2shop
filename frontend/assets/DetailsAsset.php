<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/20
 * Time: 9:19
 */

namespace frontend\assets;


use yii\web\AssetBundle;

class DetailsAsset extends AssetBundle
{
    public $basePath = '@webroot';//静态资源的硬盘路径
    public $baseUrl = '@web';//静态资源的url路径
    //需要加载的css文件
    public $css = [
        '/style/base.css',
        '/style/global.css',
        '/style/header.css',
        '/style/home.css',
        '/style/address.css',
        '/style/bottomnav.css',
        '/style/footer.css',
    ];
    //需要加载的js文件
    public $js = [
        'js/header.js',
        'js/home.js'
    ];
    //和其他静态资源管理器的依赖关系
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}