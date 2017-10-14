<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\JobTitle */

$this->title = 'Create Job Title';
$this->params['breadcrumbs'][] = ['label' => 'Job Titles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="job-title-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
