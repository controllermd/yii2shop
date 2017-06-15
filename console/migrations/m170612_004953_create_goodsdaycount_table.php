<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goodsdaycount`.
 */
class m170612_004953_create_goodsdaycount_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goodsdaycount', [
            'id' => $this->primaryKey(),
            'date'=> $this->date()->notNull()->comment('日期'),
            'count'=> $this->integer()->comment('商品数')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goodsdaycount');
    }
}
