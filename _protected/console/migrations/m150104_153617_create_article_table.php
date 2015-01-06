<?php
use yii\db\Schema;
use yii\db\Migration;

class m150104_153617_create_article_table extends Migration
{
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') 
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%article}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'title' => Schema::TYPE_STRING . ' NOT NULL',
            'summary' => Schema::TYPE_TEXT . ' NOT NULL',
            'content' => Schema::TYPE_TEXT . ' NOT NULL',
            'status' => Schema::TYPE_INTEGER . ' NOT NULL',
            'category' => Schema::TYPE_INTEGER . ' NOT NULL',          
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'FOREIGN KEY (user_id) REFERENCES {{%user}}(id)
                ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%article}}');
    }
}
