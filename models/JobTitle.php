<?php

namespace app\models;

use yii\db\ActiveRecord;

class JobTitle extends ActiveRecord 
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'job_title';
	}

	public function rules()
	{
		return [
			['title', 'required'],
			['title', 'string', 'max' => 256],
			['title', 'unique']
		];
	}

	public function getEmployees()
	{
		return $this->hasMany(Employee::className(), ['job_title_id' => 'id']);
	}
}