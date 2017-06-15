<?php

namespace backend\controllers;

use backend\models\ReviseForm;
use backend\models\User;
use yii\web\Request;

class UserController extends \yii\web\Controller
{
    
    public function actionIndex()
    {
        $admins = User::find()->all();
        return $this->render('index',['admins'=>$admins]);
    }
    public function actionAdd(){
        $admin = new User();//['scenario'=>Admin::SCENARIO_ADD]
        $admin->setScenario('add');
        $request = new Request();
        if($request->isPost){
            if($admin->load($request->post()) && $admin->validate()){
                $admin->password_hash = \Yii::$app->security->generatePasswordHash($admin->password_hash);
                \Yii::$app->session->setFlash('success','添加成功');
                $admin->save(false);
                return $this->redirect(['user/login']);
            }
        }
        return $this->render('add',['admin'=>$admin]);
    }
    public function actionEdit($id){
        $admin = User::findOne(['id'=>$id]);//['scenario'=>Admin::SCENARIO_ADD]
        $admin->setScenario('edit');
        $request = new Request();
        //var_dump($admin);exit;
        if($request->isPost){
            $admin->load($request->post());
            //var_dump($admin->username);exit();
            if($admin->validate()){
                //var_dump($admin);exit;
                \Yii::$app->session->setFlash('success','修改成功');
                $admin->save();
                return $this->redirect(['user/index']);
            }else{
                var_dump($admin->getErrors());
            }
        }
        return $this->render('edit',['admin'=>$admin]);
    }
    public function actionRevise(){
        $model = new ReviseForm();
        $request = new Request();
        if($request->isPost){
            if($model->load($request->post()) && $model->validate()){
                //var_dump($model);exit;
                $admin = \Yii::$app->user->identity;
                $admin->password_hash = \Yii::$app->security->generatePasswordHash($model->new_password);
                $admin->save(false);
                \Yii::$app->session->setFlash('success','修改成功');
                \Yii::$app->user->logout();
                 return $this->redirect(['user/login']);
            }
        }

        return $this->render('revise',['model'=>$model]);
    }
    public function actionDel($id){
        $admin = User::findOne(['id'=>$id]);
        $admin->status = 0;
        $admin->save();
        return $this->redirect(['user/index']);
    }
    public function actionLogin(){
        $login = new User();
        $login->setScenario('login');
        $request = new Request();
        if($request->isPost){
            if($login->load($request->post()) && $login->validate()){
            \Yii::$app->session->setFlash('success','登录成功');
            return $this->redirect(['goods/index']);
        }
    }
        return $this->render('login',['login'=>$login]);
    }
    public function actionLogout(){
        $id = \Yii::$app->user->id;
        $admin = User::findOne(['id'=>$id]);
        $admin->login_out = time();
        $ip = \Yii::$app->request->userIP;
        $admin->logout_ip = $ip;
        $admin->save(false);
        \Yii::$app->user->logout();
        return $this->redirect(['user/login']);
    }

}
