<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $model app\models\Employee */

$this->title = 'Create Employee';
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'employee' => $employee,
        'user' => $user,
        'attachment' => $attachment,
    ]) ?>

</div>
