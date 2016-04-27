<?php

namespace backend\controllers;

use Yii;
use backend\models\Tags;
use yii\web\Controller;
use yii\web\Response;

/**
 * ItemsController implements the CRUD actions for Items model.
 */
class TagsController extends Controller
{

	// On TagController (example)
	// actionList to return matched tags
	public function actionList($query)
	{
		
		$models = Tags::findAllByName($query);
		$items = [];

		foreach ($models as $model) {
			$items[] = ['name' => $model->name];
		}
		// We know we can use ContentNegotiator filter
		// this way is easier to show you here :)
		Yii::$app->response->format = Response::FORMAT_JSON;

		return $items;
	}

}
