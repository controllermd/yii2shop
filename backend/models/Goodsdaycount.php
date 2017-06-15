<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goodsdaycount".
 *
 * @property integer $id
 * @property string $date
 * @property integer $count
 */
class Goodsdaycount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goodsdaycount';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'required'],
            [['date'], 'safe'],
            [['count'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'date' => '日期',
            'count' => '商品数',
        ];
    }
}
