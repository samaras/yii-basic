<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */

$this->title = $user->first_name ." ". $user->surname;
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $employee->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $employee->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

     <?= DetailView::widget([
        'model' => $user,
        'attributes' => [
            'first_name',
            'surname',
            'username',
            'email',
            [
                'attribute' => 'created_at',
                'format' => ['date', 'jS/M/Y'],
            ],
        ],
    ]) ?>

     <?php
        $status = $employee->getStatus();
     ?>

    <?= DetailView::widget([
        'model' => $employee,
        'attributes' => [
            'address',
            'cellphone',
            'id_number',
            'status',
        
            [
                'attribute' => 'date_appointed',
                'format' => ['date', 'jS/M/Y'],
            ],
            [
                'attribute' => 'date_of_birth',
                'format' => ['date', 'jS/M/Y'],
            ],
            'profile_pic',
            /*
            [
                echo Html::img($model­->getProfilePicUrl(), [
                    'class'=>'img­thumbnail', 
                    'alt'=>$title, 
                    'title'=>$title
                ]);
            ],
            */
            [
                'attribute' => 'job_title_id',
                'value' => $job_title->title
            ],
            [
                'attribute' => 'department_id',
                'value' => $department->department
            ]
        ],
    ]) ?>

</div>
