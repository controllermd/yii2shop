<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/8
 * Time: 17:37
 */

namespace backend\models;

use yii\db\ActiveRecord;

class Article_category extends ActiveRecord
{
    public $code;
    static public $statusOption = [-1=>'删除',1=>'正常',0=>'隐藏'];
    static public $ishelpOption = [1=>'文章',0=>'帮助文档'];
    public function rules()
    {
        return [
            [['name','intro','sort','status','is_help'],'required'],
            ['sort','integer'],
            ['code','captcha'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'分类名称',
            'intro'=>'简介',
            'sort'=>'排序',
            'status'=>'状态',
            'is_help'=>'类型',
            'code'=>'验证码'
        ];
    }
}