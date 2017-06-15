<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/14
 * Time: 18:12
 */

namespace backend\models;


use yii\base\Model;

class ReviseForm extends Model
{
    public $password_hash;
    public $new_password;
    public $password;
    public function rules()
    {
        return [
            [['password_hash','new_password','password'],'required'],
            ['password','compare','compareAttribute'=>'new_password','message'=>'前后密码要一致'],
            ['password_hash','validatePassword']
        ];
    }
    public function attributeLabels()
    {
        return [
            'password_hash'=>'旧密码',
            'new_password'=>'新密码',
            'password'=>'确认密码',
        ];
    }
    public function validatePassword(){
        $password_hash = \Yii::$app->user->identity->password_hash;
        $password = $this->password_hash;
        if(!\Yii::$app->security->validatePassword($password,$password_hash)){
            $this->addError('password_hash','账号或者密码错误');
        }
    }
}