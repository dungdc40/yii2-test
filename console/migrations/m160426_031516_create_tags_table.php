<?php

use yii\db\Migration;
use yii\db\Schema;

class m160426_031516_create_tags_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%tags}}', [
			'id' => Schema::TYPE_PK,
			'name' => Schema::TYPE_STRING . ' NOT NULL',
			'frequency' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
		]);
    }

    public function down()
    {
        $this->dropTable('tags');
    }
}
