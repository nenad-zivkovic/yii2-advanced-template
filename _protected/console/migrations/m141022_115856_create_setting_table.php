<?php

use yii\db\Schema;
use yii\db\Migration;

class m141022_115856_create_setting_table extends Migration
{
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') 
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%setting}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'value' => Schema::TYPE_BOOLEAN . ' NOT NULL',
        ], $tableOptions);

        $this->insert('{{%setting}}', ['name' => 'Registration Needs Activation', 'value' => 0]);
        $this->insert('{{%setting}}', ['name' => 'Login With E-mail', 'value' => 0]);
    }

    public function down()
    {
        $this->dropTable('{{%setting}}');
    }
}
