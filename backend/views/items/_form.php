<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use backend\models\Vendors;
use backend\models\Types;
use dosamigos\datepicker\DatePicker;
use dosamigos\selectize\SelectizeTextInput;

/* @var $this yii\web\View */
/* @var $model backend\models\Items */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="items-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'item_name')->textInput(['maxlength' => true]) ?>
    
    <?=
        $form->field($model, 'vendor_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Vendors::find()->all(), 'id', 'name'),
            'language' => 'en',
            'options' => ['placeholder' => 'Select a vendor ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    ?>
    
    <?=
        $form->field($model, 'type_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Types::find()->all(), 'id', 'name'),
            'language' => 'en',
            'options' => ['placeholder' => 'Select a type ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    ?>
    
    <?= $form->field($model, 'serial_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'weight')->textInput() ?>

    <?= $form->field($model, 'color')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'tagValues')->widget(SelectizeTextInput::className(), [
		// calls an action that returns a JSON object with matched
		// tags
		'options' => ['class' => 'form-control'],
		'loadUrl' => ['tags/list'],
		'clientOptions' => [
			'plugins' => ['remove_button'],
			'valueField' => 'name',
			'labelField' => 'name',
			'searchField' => ['name'],
			'create' => true,
		],
	])->hint('Use commas to separate tags') ?>
	
    <?= $form->field($model, 'release_date')->widget(
        DatePicker::className(), [
            // inline too, not bad
             'inline' => false, 
             // modify template for custom rendering
            
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd'
            ]
    ]);?>

    <?= $form->field($model, 'file')->fileInput() ?>
    
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
