<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/8
 * Time: 19:21
 */

namespace backend\controllers;


use backend\models\Article;
use backend\models\Article_category;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

class ArticleController extends Controller
{
    //显示
    public function actionIndex(){
        $quest = Article::find();
        $total = $quest->count();
        $page = new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>1
        ]);
        $article = $quest->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['article'=>$article,'page'=>$page]);
    }
    //新增
    public function actionAdd(){
        $article = new Article();
        $article_categorys = Article_category::find()->all();
        $request = new Request();
        if($request->isPost){
            $article->load($request->post());
            if($article->validate()){
                $article->create_time=time();
                $article->save();
                //var_dump($article);exit();
                return $this->redirect(['article/index']);
            }
        }
        return $this->render('add',['article'=>$article,'article_categorys'=>$article_categorys]);
    }
    //修改
    public function actionEdit($id){
        $article = Article::findOne(['id'=>$id]);
        $article_categorys = Article_category::find()->all();
        $request = new Request();
        if($request->isPost){
            $article->load($request->post());
            if($article->validate()){
                $article->create_time=time();
                $article->save();
                //var_dump($article);exit();
                return $this->redirect(['article/index']);
            }
        }
        return $this->render('add',['article'=>$article,'article_categorys'=>$article_categorys]);
    }
    //删除
    public function actionDel($id){
        $article = Article::findOne(['id'=>$id]);
        $article->status = -1;
        $article->save();
        return $this->redirect(['article/index']);
    }
}