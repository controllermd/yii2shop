<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/8
 * Time: 19:21
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Article extends ActiveRecord
{
    static public $statusOption = [-1=>'删除',1=>'正常',0=>'隐藏'];
    public function getArticle_category(){
        return $this->hasOne(Article_category::className(),['id'=>'article_category_id']);
    }
    public $content;
    public function rules()
    {
        return [
            [['name','intro','sort','article_category_id','content'],'required'],
            [['sort', 'status'], 'integer']
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'文章名',
            'intro'=>'简介',
            'article_category_id'=>'分类',
            'sort'=>'排序',
            'status'=>'状态',
            'content'=>'文章详情'
        ];
    }
}