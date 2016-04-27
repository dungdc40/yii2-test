<?php

use yii\db\Migration;

class m160405_021434_create_types_table extends Migration
{
    public function up()
    {
        $this->createTable('types', [
            'id' => $this->primaryKey(),
            'name' => $this->string(512)->notNull()
        ]);
        
    }

    public function down()
    {
        $this->dropTable('types');
    }
}
