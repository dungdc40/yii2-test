<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
<div class="items-index">
	<div class="col-xs-12">
		<h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Items', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
	<div class="box">
		<div class="box-body table-responsive no-padding">
			<?php Pjax::begin(['id' => 'items']) ?>
			<?= GridView::widget([
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'tableOptions' => ['class' => 'table table-bordered'],
				'columns' => [
					['class' => 'yii\grid\SerialColumn'],
					'item_name',
					[
						'attribute' => 'vendor',
						'value' => 'vendor.name'
					],
					[
						'attribute' => 'type',
						'value' => 'type.name'
					],
					'serial_number',
					 'price',
					// 'weight',
					 'color',
					// 'release_date',
					[
						'attribute' => 'image',
						'format' => 'html',
						'value' => function($data) {					
							return $data->photo ? Html::img(Url::toRoute($data->photo,true), ['width'=>'100']) : '';	
						},
					],
					// 'tags:ntext',
					// 'created_date',

					['class' => 'yii\grid\ActionColumn'],
				],
			]); ?>
			<?php Pjax::end() ?>
			</div>
		</div>
	</div>
</div>
</div>