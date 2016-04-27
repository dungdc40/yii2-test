<?php

use yii\helpers\Html;
$this->registerJsFile('@web/js/items.js', ['depends' => [\backend\assets\AppAsset::className()]]);


/* @var $this yii\web\View */
/* @var $model backend\models\Items */

$this->title = 'Create Items';
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="items-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
