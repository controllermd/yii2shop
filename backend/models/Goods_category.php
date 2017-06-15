<?php

namespace backend\models;

use Yii;
use creocoder\nestedsets\NestedSetsBehavior;
/**
 * This is the model class for table "goods_category".
 *
 * @property integer $id
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $name
 * @property integer $parent_id
 * @property string $intro
 */
class Goods_category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_category';
    }
    public function getParent(){
        return $this->hasOne(self::className(),['id'=>'parent_id']);
    }

    /**
     * @inheritdoc
     */
    
    public function rules()
    {
        return [
            [['tree', 'lft', 'rgt', 'depth', 'parent_id'], 'integer'],
            [[ 'name', 'parent_id'], 'required'],
            [['intro'], 'string'],
            [['name'], 'string', 'max' => 255],
            ['name','unique','message'=>'商品分类名不能重复']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '商品分类名称',
            'parent_id' => '上级分类',
            'intro' => '简介',
        ];
    }
    public function behaviors() {
        return [
            [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
            ],
        ];
    }
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }
    public static function find()
    {
        return new GoodsCategoryQuery(get_called_class());
    }
}
