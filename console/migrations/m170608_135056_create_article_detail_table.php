<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_detail`.
 */
class m170608_135056_create_article_detail_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_detail', [
            'id' => $this->primaryKey(),
            'article_id'=>$this->integer()->comment('文章id'),
            'content'=>$this->text()->comment('文章内容')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_detail');
    }
}
