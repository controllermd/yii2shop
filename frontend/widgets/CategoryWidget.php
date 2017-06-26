<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/22
 * Time: 22:00
 */

namespace frontend\widgets;

use frontend\models\Goods_category;
use yii\base\Widget;

class CategoryWidget extends Widget
{
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
    }
    public function run()
    {
        //检测redis是否有商品分类缓存
        //运用redis的时候要new一个
        $redis = \Yii::$app->redis;
        /*$redis = new \Redis();
        $redis->connect('127.0.0.1',6379);*/
        //连接数据库
        $category = $redis->get('category');
        if(!$category){
            $models = Goods_category::find()->where(['parent_id'=>0])->all();
            $category =  $this->renderFile('@app/widgets/view/category.php',['models'=>$models]);
            $redis->set('category',$category);
        }
        return $category;
    }
}