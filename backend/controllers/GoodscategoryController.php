<?php

namespace backend\controllers;

use backend\components\RbacFilter;
use backend\models\Goods_category;

use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

class GoodscategoryController extends \yii\web\Controller
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
    public function actionIndex()
    {
        $model = Goods_category::find()->orderBy('tree,lft')->all();

        //$model1 = Goods_category::findOne(['id'=>$model->parent_id]);
        //$model1 = Goods_category::find()->where(['id'=>])->all();
        //var_dump($model1);exit;
        return $this->render('index',['model'=>$model]);
    }
    public function actionAdd(){
        //$this->layout = false;
        $model = new Goods_category();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //判断panent_id是否为空，如果为空就添加一级
            //var_dump($model->parent_id);exit;
            if($model->parent_id){
                //添加父类下一级目录
                $parent = Goods_category::findOne(['id'=>$model->parent_id]);
                $model->prependTo($parent);
            }else{
                //添加一级目录
                $model->makeRoot();
            }

            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['goodscategory/index']);
        }
        $category = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],Goods_category::find()->all());

        $category = Json::encode($category);
        //var_dump($category);exit;
        return $this->render('add',['model'=>$model,'category'=>$category]);
    }
    public function actionEdit($id)
    {
        //$this->layout = false;
        $model = Goods_category::findOne(['id' => $id]);
        if($model == null){
            throw new NotFoundHttpException('分类不存在');
        }
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            //判断panent_id是否为空，如果为空就添加一级
            if ($model->parent_id) {
                //添加父类下一级目录

                $parent = Goods_category::findOne(['id' => $model->parent_id]);

                $model->prependTo($parent);
            } else {
                //添加一级目录   获得原来的分类getOldAttribute
                if($model->getOldAttribute('parent_id')==0){
                    $model->save();
                }else{
                    $model->makeRoot();
                }

            }

            \Yii::$app->session->setFlash('success', '修改成功');
            return $this->redirect(['goodscategory/index']);
        }
        $category = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],Goods_category::find()->all());
        $category = Json::encode($category);
        //var_dump($category);exit;
        return $this->render('add', ['model' => $model, 'category' => $category]);
    }
    public function actionZtree(){
        $category = Goods_category::find()->asArray()->all();
        return $this->renderPartial('ztree',['category'=>$category]);
    }

}
