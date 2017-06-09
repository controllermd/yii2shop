<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;
use xj\uploadify\UploadAction;

class BrandController extends Controller
{
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
        ];
    }
    //显示
    public function actionIndex()
    {   //获取所有
        $quest = Brand::find();
        //获取总条数
        $total = $quest->count();
        //获得每页多少条，当前第几页
        $page = new Pagination(
            [
                'totalCount'=>$total,
                'defaultPageSize'=>2
            ]
        );
        $brand = $quest->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['brand'=>$brand,'page'=>$page]);
    }
    //添加
    public function actionAdd(){
        $brand = new Brand();
        $request = new Request();
        if($request->isPost){
            //接收数据
            $brand->load($request->post());
            //在验证数据前实例化上传图片对象
            //$brand->imgFile = UploadedFile::getInstance($brand,'imgFile');
            //判断数据
            if($brand->validate()){
                /*if($brand->imgFile){
                    //保存图片getAlias解析一个别名
                    $fileName = '/images/brand/'.uniqid().'.'.$brand->imgFile->extension;
                    //保存上传图片
                    $brand->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);//false是不删除他的临时文件
                    $brand->logo = $fileName;
                }*/
                \Yii::$app->session->setFlash('success','添加成功');
                $brand->save(false);
                //var_dump($brand);exit;
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add',['brand'=>$brand]);
    }
    //修改
    public function actionEdit($id){
        $brand = Brand::findOne(['id'=>$id]);
        $request = new Request();
        if($request->isPost){
            //接收数据
            $brand->load($request->post());
            //在验证数据前实例化上传图片对象
            //$brand->imgFile = UploadedFile::getInstance($brand,'imgFile');
            //判断数据
            if($brand->validate()){
                /*if($brand->imgFile){
                    //保存图片getAlias解析一个别名
                    $fileName = '/images/brand/'.uniqid().'.'.$brand->imgFile->extension;
                    //保存上传图片
                    $brand->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);//false是不删除他的临时文件
                    $brand->logo = $fileName;
                }*/
                \Yii::$app->session->setFlash('warning','修改成功');

                $brand->save(false);
                //var_dump($brand->getErrors());exit;
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add',['brand'=>$brand]);
    }
    //删除
    public function actionDel($id){
        $brand = Brand::findOne(['id'=>$id]);
        $brand->status = -1;
        //var_dump($brand->status);exit;
        \Yii::$app->session->setFlash('danger','删除成功');
        $brand->save(false);
        return $this->redirect(['brand/index']);
    }

}
