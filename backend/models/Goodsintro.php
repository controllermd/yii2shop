<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goodsintro".
 *
 * @property integer $id
 * @property integer $goods_id
 * @property string $content
 */
class Goodsintro extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goodsintro';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'goods_id' => '商品id',
            'content' => '商品描述',
        ];
    }
}
