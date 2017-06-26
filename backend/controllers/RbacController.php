<?php

namespace backend\controllers;

use backend\components\RbacFilter;
use backend\models\PermissionFrom;
use backend\models\RoleForm;
use yii\web\NotFoundHttpException;

class RbacController extends \yii\web\Controller
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
    //列表
    public function actionIndexpermission()
    {
        $models = \Yii::$app->authManager->getPermissions();
        return $this->render('indexpermission',['models'=>$models]);
    }
    //添加
    public function actionAddpermission()
    {
        $model = new PermissionFrom();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            \Yii::$app->session->setFlash('success','添加成功');
            $model->addPermission();
            return $this->redirect(['rbac/indexpermission']);
        }
        return $this->render('addpermission',['model'=>$model]);
    }
    //修改
    public function actionUpdatepermission($name){
        $permission = \Yii::$app->authManager->getPermission($name);
        if($permission == null){
            throw new NotFoundHttpException('权限不存在');
        }
        $model = new PermissionFrom();
        //将修改的权限的值赋值给表单模型
        $model->loadData($permission);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->updatePermission($name)){
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['rbac/indexpermission']);
            }
        }
        return $this->render('addpermission',['model'=>$model]);
    }
    public function actionDelpermission($name){
        $permission = \Yii::$app->authManager->getPermission($name);
        if($permission == null){
            throw new NotFoundHttpException('权限不存在');
        }
        \Yii::$app->authManager->remove($permission);
        \Yii::$app->session->setFlash('success','权限删除成功');
        return $this->redirect(['rbac/indexpermission']);
    }
    //添加角色
    public function actionAddrole(){

        $model = new RoleForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->addRole()){
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['rbac/indexrole']);
            }
        }
        return $this->render('addrole',['model'=>$model]);
    }
    //显示角色
    public function actionIndexrole(){
        $models = \Yii::$app->authManager->getRoles();
        return $this->render('indexrole',['models'=>$models]);
    }
    //修改角色
    public function actionEditrole($name){
        $role = \Yii::$app->authManager->getRole($name);
        if($role==null){
            throw new NotFoundHttpException('角色不存在');
        }

        $model = new RoleForm();
        $model->loadData($role);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->updateRole($name)){
                \Yii::$app->session->setFlash('success','角色修改成功');
                return $this->redirect(['rbac/indexrole']);
            }
        }

        return $this->render('addrole',['model'=>$model]);
    }
    //删除角色
    public function actionDelrole($name){
        $authManager = \Yii::$app->authManager->getRole($name);
        if($authManager == null){
            throw new NotFoundHttpException('角色不存在');
        }
        \Yii::$app->authManager->remove($authManager);
        \Yii::$app->session->setFlash('success','角色删除成功');
        return $this->redirect(['rbac/indexrole']);
    }
}
