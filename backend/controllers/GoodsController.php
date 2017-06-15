<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\Goods;
use backend\models\Goods_category;
use backend\models\Goodsdaycount;
use backend\models\GoodsForm;
use backend\models\Goodsintro;
use xj\uploadify\UploadAction;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Request;

class GoodsController extends \yii\web\Controller
{

    public function actionIndex()
    {
        /*$key = isset($_GET['key'])?$_GET['key']:'';
        $goods = Goods::find()->andWhere(['like','name',$key])->all();
        return $this->render('index',['goods'=>$goods]);*/
        $goods = new GoodsForm();
        $quest = Goods::find();
        $goods->search($quest);
        $page = new Pagination([
            'totalCount'=>$quest->count(),
            'pageSize'=>1
        ]);

        $models = $quest->limit($page->limit)->offset($page->offset)->all();
        return $this->render('index',['page'=>$page,'goods'=>$goods,'models'=>$models]);
    }
    public function actionAdd(){
        $goods = new Goods();
        $goodsintro = new Goodsintro();
        $goodsdaycount = new Goodsdaycount();
        $goods_category = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],Goods_category::find()->all());
        $goods_category = Json::encode($goods_category);
        $brand = ArrayHelper::map(Brand::find()->all(),'id','name');
        $request = new Request();
        if($request->isPost){
            $goods->load($request->post());
            $goodsintro->load($request->post());
            //var_dump($goodsintro);exit;
            if($goods->validate() && $goodsintro->validate()){
                $goodsday = Goodsdaycount::findOne(['date'=>date('Ymd')]);
                if($goodsday){
                    $count = $goodsday->count;
                    $count=$count+1;
                    //str_pad — 使用另一个字符串填充字符串为指定长度
                    $goods->sn =  date('Ydm').str_pad($count,5,'0',STR_PAD_LEFT);
                    $goods->create_time = time();
                    $goods->save();
                    $goodsday->count=$goodsday->count+1;

                    $goodsintro->goods_id = $goods->id;
                    $goodsday->save();
                    $goodsintro->save();
                }else{
                    $goodsdaycount->date = date('Y-m-d');
                    $goodsdaycount->count = 0;
                    $goodsdaycount->save();
                }
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['goods/index']);
            }
        }

        return $this->render('add',['goods'=>$goods,'brand'=>$brand,'goods_category'=>$goods_category,'goodsintro'=>$goodsintro]);
    }
    public function actionEdit($id){
        $goods = Goods::findOne(['id'=>$id]);
        $goodsintro = Goodsintro::findOne(['id'=>$id]);
        $goodsdaycount = new Goodsdaycount();
        $goods_category = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],Goods_category::find()->all());
        $goods_category = Json::encode($goods_category);
        $brand = ArrayHelper::map(Brand::find()->all(),'id','name');
        $request = new Request();
        if($request->isPost){
            $goods->load($request->post());
            $goodsintro->load($request->post());
            if($goods->validate() && $goodsintro->validate()){
                $goodsday = Goodsdaycount::findOne(['date'=>date('Ymd')]);
                if($goodsday){
                    $count = $goodsday->count;
                    $count=$count+1;
                    $goods->sn =  date('Ydm').str_pad($count,5,'0',STR_PAD_LEFT);
                    $goods->save();
                    $goodsday->count=$goodsday->count+1;
                    $goodsday->save();
                }else{
                    $goodsdaycount->date = date('Y-m-d');
                    $goodsdaycount->count = 0;
                    $goodsdaycount->save();
                }
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['goods/index']);
            }
        }

        return $this->render('add',['goods'=>$goods,'brand'=>$brand,'goods_category'=>$goods_category,'goodsintro'=>$goodsintro]);
    }
    public function actionDel($id){
        $goods = Goods::findOne(['id'=>$id]);
        $goods->status = 0;
        $goods->save();
        return $this->redirect(['goods/index']);
    }
    //插件
    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                /*'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },*/
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $action->output['fileUrl'] = $action->getWebUrl();
                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }
}
