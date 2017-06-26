<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;
use yii\web\IdentityInterface;
/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $login_out
 * @property integer $logout_ip
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public $password2;
    public $code;
    public $cookie;
    public $description;
    public $role=[];
    public static $statusOption = [1=>'正常',2=>'未启用',0=>'删除'];
    //获取所有权限
    public static function getRole(){
        $authManager = \Yii::$app->authManager;
        return ArrayHelper::map($authManager->getRoles(),'name','description');
    }
    public function getRoleone(){
        return $this->hasMany(Role::className(),['name'=>'username']);
    }
    public function rules()
    {
        return [
            [['username', 'password_hash'], 'required','on'=>['add','login','edit']],
            ['password2', 'required','on'=>['add']],
            ['code','captcha','on'=>['add','login']],
            ['status','string','on'=>['edit']],
            ['password2','compare','compareAttribute'=>'password_hash','message'=>'前后密码要一致','on'=>['add']],
            ['username','validateUsername','on'=>['login']],
            ['cookie','safe'],
            ['username','unique','on'=>['add']],
            ['role','safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '管理员名',
            'password_hash' => '密码',
            'password2'=>'确认密码',
            'status' => '状态',
            'code'=>'验证码',
            'cookie'=>'自动登录',
            'role'=>'添加角色'
        ];
    }
    public function validateUsername(){
        $admin = User::findOne(['username'=>$this->username]);
        if($admin){
            //用户存在  验证密码
            if(!\Yii::$app->security->validatePassword($this->password_hash,$admin->password_hash)){
                $this->addError('username','账号或者密码错误');
            }else{
                $admin->generateAuthKey();
                $admin->save(false);
                $cookie=\Yii::$app->user->authTimeout;
                \Yii::$app->user->login($admin,$this->cookie?$cookie:0);
            }
        }else{
            //账号不存在，添加错误
            $this->addError('username','账号或者密码错误');
        }
    }
    public function addRole($id){
        $authManager = \Yii::$app->authManager;
        foreach ($this->role as $roleName){
            $role = $authManager->getRole($roleName);
            if($role){
                $authManager->assign($role,$id);
            }
        }
    }
    public function updateRole($id){
        $authManager = \Yii::$app->authManager;
        $authManager->revokeAll($id);
        foreach ($this->role as $roleName) {
            $role = $authManager->getRole($roleName);
            if ($role) {
                $authManager->assign($role, $id);
            }
        }
    }
    public function loadData($id){
        foreach (\Yii::$app->authManager->getRolesByUser($id) as $role){
            $this->role[] = $role->name;
        }
    }
    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    //预先存入AuthKey到服务器
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    //获取AuthKey
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    //将AuthKey存入的时候生成
    public function generateAuthKey()
    {
        //生成一个随机字符串
       return $this->auth_key = Yii::$app->security->generateRandomString();
    }
}
