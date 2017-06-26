<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/8
 * Time: 19:21
 */

namespace backend\controllers;


use backend\components\RbacFilter;
use backend\models\Article;
use backend\models\Article_category;
use backend\models\Article_detail;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

class ArticleController extends Controller
{
    //设置权限
    public function behaviors(){
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
                'only'=>['add','index','edit','del'],
            ]
        ];
    }
    //显示
    public function actionIndex(){
        $quest = Article::find();
        $total = $quest->count();
        $page = new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>2
        ]);
        $article = $quest->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['article'=>$article,'page'=>$page]);
    }
    //新增
    public function actionAdd(){
        $article = new Article();
        $article_detail = new Article_detail();
        $article_categorys = Article_category::find()->all();
        $request = new Request();
        if($request->isPost){
            //这里就把表单里面的数据接收了，下面不需要再赋值
            $article->load($request->post());
            $article_detail->load($request->post());
            //var_dump($article_detail,$article);exit;
            if($article->validate() && $article_detail->validate()){
                $article->create_time=time();
                $res = $article->save();
                $article_detail->article_id = $article->id;
                $article_detail->save();
                return $this->redirect(['article/index']);
            }
        }
        return $this->render('add',['article'=>$article,'article_detail'=>$article_detail,'article_categorys'=>$article_categorys]);
    }
    //修改
    public function actionEdit($id){

        $article = Article::findOne(['id'=>$id]);
        $article_detail = Article_detail::findOne(['article_id'=>$id]);
        $article_categorys = Article_category::find()->all();
        //可以用
        //$article_detail = $article->Article
        $request = new Request();
        if($request->isPost){
            $article->load($request->post());
            $article_detail->load($request->post());
            if($article->validate() && $article_detail->validate()){
                $article->create_time=time();
                $article->save();
                $article_detail->save();
                //var_dump($article->getErrors());exit;
                return $this->redirect(['article/index']);
            }
        }
        return $this->render('add',['article'=>$article,'article_detail'=>$article_detail,'article_categorys'=>$article_categorys]);
    }
    //删除
    public function actionDel($id){
        $article = Article::findOne(['id'=>$id]);
        $article->status = -1;
        $article->save();
        return $this->redirect(['article/index']);
    }
    public function actionDetail($id){
        $article = Article::findOne(['id'=>$id]);
        $articled = Article_detail::findOne(['article_id'=>$article->id]);
        return $this->render('detail',['articled'=>$articled]);
    }
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }
}