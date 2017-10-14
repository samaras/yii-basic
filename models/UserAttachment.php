<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_attachment".
 *
 * @property integer $id
 * @property integer $employee_id
 * @property integer $attachment_id
 *
 * @property Employee $employee
 * @property Attachment $attachment
 */
class UserAttachment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_attachment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_id', 'attachment_id'], 'required'],
            [['employee_id', 'attachment_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'employee_id' => 'Employee',
            'attachment_id' => 'Attachment File',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id' => 'employee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachment()
    {
        return $this->hasOne(Attachment::className(), ['id' => 'attachment_id']);
    }
}
