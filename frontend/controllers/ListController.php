<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/21
 * Time: 19:00
 */

namespace frontend\controllers;


use frontend\models\Goods;
use yii\web\Controller;

class ListController extends Controller
{
    public $layout = 'list';
    public function actionList($id){
        //$model = Goods_category::find()->where(['id'=>$id])->all();
        $models = Goods::find()->where(['goods_category_id'=>$id])->all();
        return $this->render('list',['models'=>$models]);

        //var_dump($models);exit;

    }
}