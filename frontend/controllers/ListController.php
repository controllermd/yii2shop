<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/21
 * Time: 19:00
 */

namespace frontend\controllers;


use frontend\components\SphinxClient;
use frontend\models\Goods;
use yii\helpers\ArrayHelper;
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
    public function actionTest(){
        $keyword = \Yii::$app->request->get('keyword');
        $cl = new SphinxClient();
        $cl->SetServer ( '127.0.0.1', 9312);
        $cl->SetConnectTimeout ( 10 );
        $cl->SetArrayResult ( true );
        $cl->SetMatchMode ( SPH_MATCH_ALL);//设置全文查询的模式匹配
        $cl->SetLimits(0, 1000);
        //$info = '华为';//查询的关键字
        $res = $cl->Query($keyword, 'goods');//goods是配置的名称
        //var_dump($res);exit;
        if(isset($res['matches'])){
            $ids = ArrayHelper::map($res['matches'],'id','id');
            $models = Goods::find()->where(['in','id',$ids])->all();//in就是传过来的值是否在这个
        }else{
            $models = Goods::find()->all();
        }

        $keywords = array_keys($res['words']);
        $options = array(
            'before_match' => '<span style="color:red;">',
            'after_match' => '</span>',
            'chunk_separator' => '...',
            'limit' => 80, //如果内容超过80个字符，就使用...隐藏多余的的内容
        );
        //关键字高亮
//        var_dump($models);exit;
        foreach ($models as $index => $item) {
            $name = $cl->BuildExcerpts([$item->name], 'goods', implode(',', $keywords), $options); //使用的索引不能写*，关键字可以使用空格、逗号等符号做分隔，放心，sphinx很智能，会给你拆分的
            $models[$index]->name = $name[0];
//            var_dump($name);
        }
        return $this->render('list',['models'=>$models]);
    }
}