<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/8
 * Time: 17:38
 */

namespace backend\controllers;


use backend\models\Article_category;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

class ArticlecategoryController extends Controller
{
    //显示
    public function actionIndex(){
        //获取所有信息
       $query = Article_category::find();
        //总条数
        $total = $query->count();
        //每页显示几条，当前是第几条
        $page = new Pagination(
            [
                'totalCount'=>$total,
                'defaultPageSize'=>1
            ]
        );
        $article = $query->offset($page->offset)->limit($page->limit)->all();
       return $this->render('index',['article'=>$article,'page'=>$page]);
    }
    //新增
    public function actionAdd(){
        $article = new Article_category();
        $request = new Request();
        if($request->isPost){
            $article->load($request->post());
            if($article->validate()){
                $article->save(false);
                return $this->redirect(['articlecategory/index']);
            }
        }
        return $this->render('add',['article'=>$article]);
    }
    //修改
    public function actionEdit($id){
        $article = Article_category::findOne(['id'=>$id]);
        $request = new Request();
        if($request->isPost){
            $article->load($request->post());
            if($article->validate()){
                $article->save(false);
                return $this->redirect(['articlecategory/index']);
            }
        }
        return $this->render('add',['article'=>$article]);
    }
    //删除
    public function actionDel($id){
        $article = Article_category::findOne(['id'=>$id]);
        $article->status = -1;
        \Yii::$app->session->setFlash('danger','删除成功');
        $article->save(false);
        return $this->redirect(['articlecategory/index']);
    }
}