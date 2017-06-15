<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "admin".
 *
 * @property integer $id
 * @property string $name
 * @property string $password
 * @property integer $statue
 * @property integer $login_out
 * @property integer $logout_ip
 */
class Admin extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return 'admin';
    }

    /**
     * @inheritdoc
     */
    public $password2;
    public $code;
    public static $statusOption = [1=>'使用中',2=>'未使用',0=>'删除'];
    /*const SCENARIO_ADD = 'add';
    const SCENARIO_EDIT = 'edit';
    public function scenarios(){
        //调用父类方法，并追加
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_ADD] = ['name','password','passwoed2','code'];
        $scenarios[self::SCENARIO_EDIT] = ['name'];
    }*/
    public function rules()
    {
        return [
            [['name', 'password'], 'required','on'=>['add','login']],
            ['password2', 'required','on'=>['add']],
            [['name'], 'string', 'max' => 20],
            //[['statue','login_out','logout_id'],'integer'],
            ['code','captcha','on'=>['add','login']],
            [['password'], 'string', 'max' => 100,'on'=>['add','login']],
            ['password2','compare','compareAttribute'=>'password','message'=>'前后密码要一致','on'=>['add']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '管理员名字',
            'password' => '密码',
            'password2' => '确认密码',
            'code'=>'验证码'
        ];
    }
}
