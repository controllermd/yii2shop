<?php

namespace backend\models;

use yii\db\ActiveRecord;
/**
 * This is the model class for table "article_detail".
 *
 * @property integer $id
 * @property integer $article_id
 * @property string $content
 */
class Article_detail extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_detail';
    }

    /**
     * @inheritdoc
     */
    public function getArticle(){
        return $this->hasOne(Article::className(),['id'=>'article_id']);
    }
    public function rules()
    {
        return [
            [['article_id'], 'integer'],
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'article_id' => '文章',
            'content' => '文章内容',
        ];
    }
}
