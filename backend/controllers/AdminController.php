<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\User;
use backend\models\Items;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class AdminController extends Controller
{
	CONST NUMBER_LASTEST_ITEMS = 5;
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow' => true,
						'matchCallback' => function () {
							if (!Yii::$app->user->isGuest) {

								return User::validateAdmin(Yii::$app->user->id);
							}
							return false;
						},
					],
				],
				'denyCallback' => function () {

			// not allow to access, redirect to main page
			$this->redirect(['/items']);
		}
			]
		];
	}

	public function init()
	{
		$this->layout = 'dashboard';
	}

	/**
	 * Lists all Users models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$numItems = Items::getTotalCount();
		$avgPrice = Items::getAveragePrice();
		$typePercent = Items::getPercentageItemsPerType();
		$lastestItems = Items::getLastestItems(self::NUMBER_LASTEST_ITEMS);
		
		return $this->render('index',[
			'numberItems' => $numItems,
			'averagePrice' => $avgPrice,
			'percenPerType' => $typePercent,
			'lastestItems' => $lastestItems
		]);
	}

}
