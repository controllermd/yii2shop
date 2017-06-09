<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m170608_071112_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brand', [
            'id' => $this->primaryKey(),
//            name varchar﴾50﴿ 名称
            'name'=>$this->string(255)->notNull()->comment('名称'),
//            intro text 简介
            'intro'=>$this->text()->comment('简介'),
//            logo varchar﴾255﴿ LOGO图片
            'logo'=>$this->string(255)->comment('logo图片'),
//            sort int﴾11﴿ 排序
            'sort'=>$this->integer()->comment('排序'),
//            status int﴾2﴿ 状态﴾‐1删除 0隐藏 1正常﴿
            'status'=>$this->integer(2)->comment('状态')

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('brand');
    }
}
