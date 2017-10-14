<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "employee"
 * 
 * @property $address;
 * @property $cellphone;
 * @property $id_number;
 * @property $status;
 * @property $date_appointed;
 * @property $date_of_birth;
 * @property $profile_pic;
 * @property $job_title_id;
 * @property $user_id;
 * @property $department_id;
 *
 * @property JobTitle $job_title
 * @property User $user
 * @property Department $department
 */
class Employee extends ActiveRecord 
{
	const USER_ACTIVE  = 1;
	const USER_INACTIVE = 0;

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'employee';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['address','cellphone','id_number','status','date_appointed','date_of_birth', 'user_id', 'job_title_id', 'department_id'], 'required'],
			['cellphone', 'string', 'min'=> 10, 'max'=> 10],
			['id_number', 'string', 'min'=> 13, 'max'=> 13],
			['date_appointed', 'date', 'format' => 'php:Y-m-d'],
			['date_of_birth', 'date', 'format' => 'php:Y-m-d'],
			[['profile_pic'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, gif'],
			[['address','cellphone','id_number','status','date_appointed','date_of_birth','profile_pic'], 'safe'],
		];
	}

	public function uploadProfilePic($proPic, $imagePath)
	{
		if($this->validate()) {
			$proPic->saveAs($imagePath);
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Get the users image url
	 *
	 * @return $string url
	 */
	public function getProfilePicUrl()
	{
		$proPicBaseUrl = Yii::$app­->urlManager-­>baseUrl . "/uploads/profilepics/";
		$url = $proPicBaseUrl . $this->profile_pic;

		return $url;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'address' => 'Address',
			'cellphone' => 'Cellphone',
			'id_number' => 'ID Number',
			'status' => 'Status',
			'date_appointed' => 'Date Appointed',
			'date_of_birth' => 'Date of Birth',
			'profile_pic' => 'Profile Pic',
			'job_title_id' => 'Job Title',
			'user_id' => 'User',
			'department_id' => 'Department',
		];
	}

	/**
	 * An array of statuses
	 *
	 * @return array
	 */
	public function getStatus()
	{
		return array(self::USER_ACTIVE => 'Active', self::USER_INACTIVE => 'InActive');
	}

	/**
	 * Returns the text version of the status
	 *
	 * @return string 
	 */
	public function getStatusLabel()
	{
		$options = $this->getStatus();
		return $options;
	}

	/**
	 * Delete the users profile pic
	 *
	 * @var string $path
	 * @var string $filename
	 * @return void
	 */
	public function deleteProfilePic($path, $filename)
	{
		$file = $path.$filename;
		if(!empty($file) && file_exists($file)) {
			// delete file
			unlink($file);
		}
	}

	/** 
	 * Department
	 *
	 */
	public function getDepartment()
	{
		return $this->hasOne(Department::className(), ['id' => 'department_id']);
	}

	/**
	 * Job Title
	 */
	public function getJobTitle()
	{
		return $this->hasOne(JobTitle::className(), ['id' => 'job_title_id']);
	}

	/**
	 * User
	 */
	public function getUser()
	{
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}

	public function getAttachments()
	{
		return $this->hasMany(Attachment::className(), ['id' => 'attachment_id'])->viaTable('user_attachment', ['employee_id' => 'id']);
	}
}