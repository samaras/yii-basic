<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */

$this->title = 'Update Employee: ' . ' ' . $user->first_name .' '. $user->surname;
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $employee->id, 'url' => ['view', 'id' => $employee->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="employee-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'employee' => $employee,
        'user' => $user,
        'job_title' => $employee->jobTitle,
        'department' => $employee->department,
        'attachment' => $attachment
    ]) ?>

</div>
