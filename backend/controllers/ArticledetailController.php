<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/9
 * Time: 11:37
 */

namespace backend\controllers;

use backend\models\Article;
use backend\models\Article_detail;
use yii\web\Controller;
use yii\web\Request;

class ArticledetailController extends Controller
{
    
    public function actionIndex(){
        $articel = Article_detail::find()->all();
        return $this->render('index',['article'=>$articel]);
    }
    public function actionAdd(){
        $article = new Article_detail();
        $article_datail = Article::find()->all();
        $request = new Request();
        if($request->isPost){
            $article->load($request->post());
            if($article->validate()){
                \Yii::$app->session->setFlash('success','新增成功');
                $article->save();
                return $this->redirect(['articledetail/index']);
            }
        }
        return $this->render('add',['article'=>$article,'article_datail'=>$article_datail]);
    }
    public function actionEdit($id){
        $article = Article_detail::findOne(['id'=>$id]);
        $article_datail = Article::find()->all();
        $request = new Request();
        if($request->isPost){
            $article->load($request->post());
            if($article->validate()){
                \Yii::$app->session->setFlash('warning','修改成功');
                $article->save();
                return $this->redirect(['articledetail/index']);
            }
        }
        return $this->render('add',['article'=>$article,'article_datail'=>$article_datail]);
    }
    public function actionDel($id){
        $article = Article_detail::findOne(['id'=>$id]);
        \Yii::$app->session->setFlash('danger','删除成功');
        $article->delete();
        return $this->redirect(['articledetail/index']);
    }
}