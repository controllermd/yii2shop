<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goodsintro`.
 */
class m170612_010213_create_goodsintro_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goodsintro', [
            'id' => $this->primaryKey(),
            'goods_id'=>$this->integer()->comment('商品id'),
            'content'=>$this->text()->comment('商品描述')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goodsintro');
    }
}
