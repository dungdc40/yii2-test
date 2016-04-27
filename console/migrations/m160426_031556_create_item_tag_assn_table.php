<?php

use yii\db\Migration;
use yii\db\Schema;

class m160426_031556_create_item_tag_assn_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%item_tag_assn}}', [
			'item_id' => Schema::TYPE_INTEGER . ' NOT NULL',
			'tag_id' => Schema::TYPE_INTEGER . ' NOT NULL',
		]);

		$this->addPrimaryKey('', '{{%item_tag_assn}}', ['item_id', 'tag_id']);
    }

    public function down()
    {
        $this->dropTable('item_tag_assn_table');
    }
}
