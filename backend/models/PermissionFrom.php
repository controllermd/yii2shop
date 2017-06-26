<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/16
 * Time: 10:04
 */

namespace backend\models;


use yii\base\Model;
use yii\rbac\Permission;
use yii\web\NotFoundHttpException;

class PermissionFrom extends Model
{
    public $name;
    public $description;
    public function rules()
    {
        return [
            [['name','description'],'required'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'权限名称',
            'description'=>'权限简介'
        ];
    }
    public function addPermission(){
        $authManager = \Yii::$app->authManager;
        //判断权限是否存在
        if($authManager->getPermission($this->name)){
            $this->addError('name','权限已经存在');
        }else{
            //添加权限
            $permission = $authManager->createPermission($this->name);
            //var_dump($permission);exit;
            $permission->description = $this->description;
            return $authManager->add($permission);
        }
        return false;
    }
    //从权限中加载数据
    public function loadData(Permission $permission){
        $this->name = $permission->name;
        $this->description = $permission->description;
    }
    public function updatePermission($name){
        $authManager = \Yii::$app->authManager;
        //获取要修改的对象
        $permission = $authManager->getPermission($name);
        //判断修改后的权限名称是否存在
        if($name != $this->name && $authManager->getPermission($this->name)){
            $this->addError('name','权限已存在');
        }else{

            $permission->name = $this->name;
            $permission->description = $this->description;
            //更新
            return $authManager->update($name,$permission);
        }
        return false;
    }
    
}