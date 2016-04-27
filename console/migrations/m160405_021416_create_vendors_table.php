<?php

use yii\db\Migration;

class m160405_021416_create_vendors_table extends Migration
{
    public function up()
    {
        $this->createTable('vendors', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'logo' => $this->string(512)
        ]);
         

        
    }

    public function down()
    {
        $this->dropTable('vendors');
    }
}
