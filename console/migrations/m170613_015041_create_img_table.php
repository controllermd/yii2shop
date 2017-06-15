<?php

use yii\db\Migration;

/**
 * Handles the creation of table `img`.
 */
class m170613_015041_create_img_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('img', [
            'id' => $this->primaryKey(),
            'goods_id'=> $this->integer()->notNull()->comment('父类id'),
            'url'=> $this->string()->comment('图片地址'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('img');
    }
}
