<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="users-index">

    <h1 id="page-title"><?= Html::encode($this->title) ?></h1>
	<div class="box">
	
	<div id="notification"></div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	
	<?php $form = ActiveForm::begin(
			[
				'id' => 'UserForm', 
				'action' => Url::to(['users/update']),				
			]); ?>
	<?php Pjax::begin(['id' => 'users']) ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'username',           
			[
				'attribute' => 'is_admin',
				'content' => function ($model) {
					$checked = $model['is_admin'] ? 'checked' : '';
					$hiddenValue = $checked ? 1 : 0;
					$adminCheckbox = '<input '.$checked.' type="checkbox"'						
							. 'name="isAdmin['.$model['id'].']"/>';
					$hiddenAdmin = '<input type="hidden" class="daily-noti-hidden" '
							. 'value="'.$hiddenValue.'" name="isAdminHidden['.$model['id'].']"/>';
					return $adminCheckbox.$hiddenAdmin;
				},
				'options' => ['style' => 'width: 20%;'],
				'header' => 'Admin',
			],
        ],
    ]); ?>
	<?php Pjax::end() ?>
	<div class="form-group">
        <?= Html::submitButton('Update', ['class' => 'btn btn-success', 
										'onclick' => "return confirm('Are you sure?')"
			]) ?>
    </div>
	<?php ActiveForm::end(); ?>

</div>
</div>
<?php
$script = <<< JS
$('form#UserForm').on('beforeSubmit', function(e){
    var \$form = $(this);
    $.post(
        \$form.attr("action"),
        \$form.serialize()
    ).done(function(result){
        var html = '<div class="alert alert-success">'
			+ '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
			+ result + '</div>';
		$('#notification').html(html);
    }).fail(function(){
        console.log("server error");
    });
    
    return false;
})
JS;

$this->registerJs($script);
?>