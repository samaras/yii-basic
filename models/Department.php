<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Department extends ActiveRecord 
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'department';
	}

	public function rules()
	{
		return [
			['department', 'required'],
			['department', 'string', 'max' => 256],
			['department', 'unique']
		];
	}

	public function getEmployees()
	{
		return $this->hasMany(Employee::className(), ['department_id' => 'id']);
	}
}