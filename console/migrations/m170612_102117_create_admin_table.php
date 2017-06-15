<?php

use yii\db\Migration;

/**
 * Handles the creation of table `admin`.
 */
class m170612_102117_create_admin_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('admin', [
            'id' => $this->primaryKey(),
            'name'=> $this->string(20)->notNull()->comment('管理员名字'),
            'password'=> $this->string(100)->notNull()->comment('密码'),
            'statue'=> $this->smallInteger(1)->comment('状态'),
            'login_out'=> $this->integer()->comment('最后登录时间'),
            'logout_ip'=> $this->integer()->comment('最后登录ip')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('admin');
    }
}
