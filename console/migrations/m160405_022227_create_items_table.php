<?php

use yii\db\Migration;

class m160405_022227_create_items_table extends Migration
{
    public function up()
    {
        $this->createTable('items', [
            'id' => $this->primaryKey(),
            'item_name' => $this->string(255)->notNull(),
            'vendor_id' => $this->integer()->notNull(),
            'type_id' => $this->integer()->notNull(),
            'serial_number' => $this->string(125)->notNull(),
            'price' => $this->float()->notNull(),
            'weight' => $this->float()->notNull(),
            'color' => $this->string(125)->notNull(),
            'release_date' => $this->date()->notNull(),
            'photo' => $this->string(512),
            'created_date' => $this->timestamp()->notNull()
        ]);
        
        $this->createIndex('vendor','items','vendor_id');
        $this->createIndex('type','items','type_id');
        
        $this->addForeignKey('items_ibfk_1', 'items', 'vendor_id', 'vendors', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('items_ibfk_2', 'items', 'type_id', 'types', 'id', 'RESTRICT', 'RESTRICT');

    }

    public function down()
    {
        $this->dropTable('items');
    }
}
