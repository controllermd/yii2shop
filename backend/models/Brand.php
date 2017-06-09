<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "brand".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property string $logo
 * @property integer $sort
 * @property integer $status
 */
class Brand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public $code;
    //public $imgFile;
    //验证单选
    static public $statusOption=[1=>'正常',0=>'隐藏',-1=>'删除'];
    public function rules()
    {
        return [
            [['name','intro','sort'], 'required'],
            ['code','captcha'],
            ['logo','string'],
            [['intro'], 'string'],
            [['sort', 'status'], 'integer'],
            //['imgFile','file','extensions'=>['jpg','png','gif'],'skipOnEmpty'=>false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '品牌名称',
            'intro' => '简介',
            'logo' => 'logo图片',
            'sort' => '排序',
            'status' => '状态',
            'code'=>'验证码'
        ];
    }
}