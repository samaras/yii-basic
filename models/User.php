<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            [['first_name', 'surname', 'password', 'email', 'username'], 'required'],
            ['email', 'email'],
            [['first_name', 'surname', 'username', 'auth_key', 'password'], 'string', 'max' => 255],
            [['username', 'email'], 'unique'],
            ['role', 'default', 'value' => 'employer']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }
    

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Set password 
     *
     * @param string $password password to hash
     * @return void
     */
     public function setPassword()
     {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
     } 

     public function generateAuthKey()
     {
        $this->auth_key = Yii::$app->security->generateRandomString($length = 255);
     }

     /**
      * Before saving the user into the table
      *
      * @return boolean
      */
     public function beforeSave($insert)
     {
        $return = parent::beforeSave($insert);

        $this->password = Yii::$app->security->generatePasswordHash($this->password);
        $this->password_hash = Yii::$app->security->generatePasswordHash($this->password); // TODO: need to remove one of these
        $this->created_at = date('Y-m-d'); 
        if ($this->isNewRecord)
            $this->auth_key = Yii::$app->security->generateRandomKey($length = 255);

        return $return;
     }

     /**
      *
      *
      */
     public function getEmployee()
     {
        return $this->hasOne(Employee::className(), ['id' => 'user_id']);
     }
}
