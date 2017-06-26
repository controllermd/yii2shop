<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/21
 * Time: 14:43
 */

namespace frontend\controllers;


use frontend\models\Goods_category;
use yii\web\Controller;

class IndexController extends Controller
{
    public $layout = 'index';
    public function actionIndex()
    {
        $models = Goods_category::find()->where(['parent_id'=>0])->all();
        return $this->render('index',['models'=>$models]);
    }
}