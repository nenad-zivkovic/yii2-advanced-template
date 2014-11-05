<?php

use yii\db\Schema;
use yii\db\Migration;

class m141022_115922_create_session_table extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;

        // here we will set data type for some popular DBMS, if your DBMS is not listed,
        // you will have to update this code
        if ($this->db->driverName === 'mysql') 
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
            $dataType = 'LONGBLOB';
        }
        elseif ($this->db->driverName === 'pgsql') 
        {
            $dataType = 'BYTEA';
        }
        else
        {
            // mssql, oracle, sqlite, cubrid
            $dataType = 'BLOB';
        }

        $this->createTable('{{%session}}', [
            'id' => 'CHAR(64) NOT NULL PRIMARY KEY',
            'expire' => Schema::TYPE_INTEGER . ' NOT NULL',
            'data' => ''.$dataType.' NOT NULL',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%session}}');
    }
}
