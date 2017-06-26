<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/17
 * Time: 21:51
 */

namespace backend\models;


use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;
use yii\rbac\Role;
use yii\web\NotFoundHttpException;

class RoleForm extends Model
{
    public $name;
    public $description;
    public $permission = [];
    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['permission','safe']//表示这个不需要验证
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'角色名',
            'description'=>'角色简介',
            'permission'=>'角色权限'
        ];
    }
    //获取所有权限
    public static function getPermission(){
        $authManager = \Yii::$app->authManager;
        return ArrayHelper::map($authManager->getPermissions(),'name','description');
    }
    public function addRole(){
        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole($this->name);
        if($role){
            $this->addError('name','角色已经存在');
        }else{
            $role = $authManager->createRole($this->name);
            $role->description = $this->description;
            if($authManager->add($role)){
                //得到选择的角色，遍历写入数据库
                foreach ($this->permission as $permissionsName){
                    $permission = $authManager->getPermission($permissionsName);
                    if($permission)$authManager->addChild($role,$permission);
                }
                return true;
            }
        }
        return false;
    }
    public function updateRole($name){
        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole($name);
        $role->name = $this->name;
        $role->description = $this->description;
        if($name != $this->name && $authManager->getRole($this->name)){
            $this->addError('name','角色名称已存在');
        }else{
            if($authManager->update($name,$role)){
                //去掉所有与该角色关联的权限
                $authManager->removeChildren($role);
                //关联该角色的权限
                foreach ($this->permission as $permissionName){
                    $permission = $authManager->getPermission($permissionName);
                    if($permission) $authManager->addChild($role,$permission);
                }
                return true;
            }
        }
        return false;
    }

    //从权限中加载数据
    public function loadData(Role $role){
        $this->name = $role->name;
        $this->description = $role->description;
        $permissions = \Yii::$app->authManager->getPermissionsByRole($role->name);
        //$this->permissions = ['brand/edit','brand/index'];
        foreach ($permissions as $permission){
            $this->permission[]=$permission->name;
        }

    }
}