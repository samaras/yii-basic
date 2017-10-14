<?php

use yii\db\Schema;
use yii\db\Migration;

class m151109_205145_create_user_table extends Migration
{
    public function up()
    {
        $this->createTable('users', [
            'id' => 'pk',
            'first_name' => Schema::TYPE_STRING . ' NOT NULL',
            'last_name' => Schema::TYPE_STRING . ' NOT NULL',
            'email' => Schema::TYPE_EMAIL . ' NOT NULL',
            'password' => Schema::TYPE_STRING,
            'created_at' => Schema::TYPE_DATETIME . ' NOT NULL',
            'last_login' => Schema::TYPE_DATETIME . ' NOT NULL',
        ]);

        $this->createTable('employees', [
            'id' => 'pk',
            'address' => Schema::TYPE_STRING . ' NOT NULL',
            'celphone' => Schema::TYPE_STRING . ' NOT NULL',,
            'id_number' => Schema::TYPE_NUMBER . ' NOT NULL',,
            'status' => Schema::TYPE_BOOLEAN,
            'date_appointed' => Schema::TYPE_DATE,
            'date_of_birth' => Schema::TYPE_DATE,
            'profile_pic' => Schema::TYPE_STRING, 
        ]);

        $this->addForeignKey('job_title_id', 'employees', 'job_titles', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('user_id', 'employees', 'users', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('departments', [
            'id' => Schema::TYPE_PK,
            'department' => Schema::TYPE_STRING . ' NOT NULL'
        ]);
    }

    public function down()
    {
        $this->dropTable('users');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
