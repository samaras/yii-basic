<?php

use yii\helpers\Html;
use yii\helpers\BaseHtml;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;
use \kartik\file\FileInput;
use \app\models\User;
use \app\models\JobTitle;
use \app\models\Department;
use \app\models\Attachment;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="model">
        <?php echo $form->errorSummary([$employee, $user]); ?>

        <?= BaseHtml::activeHiddenInput($employee, 'user_id'); ?>

        <?= $form->field($user, "first_name")->textInput() ?>

        <?= $form->field($user, "surname")->textInput() ?>

        <?= $form->field($user, "email")->textInput() ?>

        <?= $form->field($user, "username")->textInput() ?>

        <?= $form->field($user, "password")->passwordInput() ?>

        <?= $form->field($employee, 'address')->textInput(['maxlength' => true]) ?>

        <?= $form->field($employee, 'cellphone')->textInput(['maxlength' => true]) ?>

        <?= $form->field($employee, 'id_number')->textInput(['maxlength' => true]) ?>

        <?= $form->field($employee, 'status')->dropDownList($employee->getStatusLabel())->label('Employee Status') ?>

        <br />
        <label>Date Appointed</label>
        <br />
        <?php 
            $value = date('d-m-Y');

            echo DatePicker::widget([
                'language' => 'en',
                'model' => $employee,
                'attribute' => 'date_appointed',
                'clientOptions' => [
                    'dateFormat' => 'yy-mm-dd'
                ]
            ]);
        ?>       
        <br />
        <br />
        <label>Date of Birth</label>
        <br />
        <?php 
            $value = date('01-11-1997');

            echo DatePicker::widget([
                'language' => 'en',
                'model' => $employee,
                'attribute' => 'date_of_birth',
                'clientOptions' => [
                    'dateFormat' => 'yy-mm-dd'
                ]
            ]);
        ?>
        <br />
        <br />
        <?= $form->field($employee, 'job_title_id')->dropDownList(ArrayHelper::map(JobTitle::find()->all(), 'id', function($jobtitle, $defaultValue){
                return sprintf('-- %s --', $jobtitle->title);
            })); ?>

        <?= $form->field($employee, 'department_id')->dropDownList(ArrayHelper::map(Department::find()->all(), 'id', function($department, $defaultValue){
                return sprintf('-- %s --', $department->department);
            })); ?>


        <?= $form->field($employee, 'profile_pic')->widget(FileInput::className(), [
                'options' => ['accept' => 'image/*'],
                'pluginOptions' => ['allowedFileExtensions' => 'jpg, png']
            ]);
        ?>

        <hr />

        <div class="form-group">
            <h4 style="text-decoration: underline">User Attachments:</h4>

            <?= $form->field($attachment, 'filename[]')->widget(FileInput::className(), [
                    'options' => [
                        'multiple' => true 
                    ],
                    'pluginOptions' => [
                        'uploadUrl' => Url::to(['/uploads/attachments']),
                        'maxFileCount' => 10
                    ]
                ]);
            ?>
        </div>

        <br />

        <div class="form-group">
            <?= Html::submitButton($employee->isNewRecord ? 'Create' : 'Update', ['class' => $employee->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
