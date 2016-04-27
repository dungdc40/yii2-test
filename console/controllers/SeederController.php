<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\db\Migration;
use common\models\User;

class SeederController extends Controller
{
    // The command "yii example/create test" will call "actionCreate('test')"
    public function actionSeed() 
    {
		
		// add user
		$user = new User();
        $user->username = 'admin';
        $user->email = 'admin@gmail.com';
		$user->is_admin = 1;
        $user->setPassword('123456');
        $user->generateAuthKey();
		$user->save();
		
		// add user
		$user = new User();
        $user->username = 'user';
        $user->email = 'user@gmail.com';
		$user->is_admin = 0;
        $user->setPassword('123456');
        $user->generateAuthKey();
		$user->save();
		
        // poppuldate table 'types'
        $db = new Migration();
        $db->dropForeignKey('items_ibfk_1', 'items');
        $db->dropForeignKey('items_ibfk_2', 'items');
        
        $seeder = new \tebazil\yii2seeder\Seeder();
        $array =
        [
            ['Phone'],
            ['Tablet'],
            ['Laptop']
        ];
        $columnConfig = ['name'];

        $seeder->table('types')->data($array, $columnConfig)->rowQuantity(3);
        $seeder->refill();
        
        
        // poppuldate table 'auth_item_child'
        $array =
        [
            ['Apple', 'images/apple.png'],
            ['Asus', 'images/asus.png'],
            ['Microsoft', 'images/microsoft.png'],
            ['LG', 'images/lg.png']
        ];
        $columnConfig = ['name', 'logo'];

        $seeder->table('vendors')->data($array, $columnConfig)->rowQuantity(4);
        $seeder->refill();
        
        $db->addForeignKey('items_ibfk_1', 'items', 'vendor_id', 'vendors', 'id', 'RESTRICT', 'RESTRICT');
        $db->addForeignKey('items_ibfk_2', 'items', 'type_id', 'types', 'id', 'RESTRICT', 'RESTRICT');
		
		// poppuldate table 'auth_item_child'
        $array =
        [
            ['luxurios'],
            ['small'],
            ['smart'],
            ['cheap']
        ];
        $columnConfig = ['name'];

        $seeder->table('tags')->data($array, $columnConfig)->rowQuantity(4);
        $seeder->refill();
        return 0;
    }
}

