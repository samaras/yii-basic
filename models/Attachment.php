<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "attachment".
 *
 * @property integer $id
 * @property string $filename
 * @property string $type
 * @property integer $size
 * @property string $date_uploaded
 *
 * @property UserAttachment $userAttachment
 */
class Attachment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attachment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['size', 'date_uploaded'], 'required'],
            [['size'], 'integer'],
            [['date_uploaded'], 'safe'],
            [['filename'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 5],
            [['type'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filename' => 'Attached File',
            'type' => 'Type',
            'size' => 'Size',
            'date_uploaded' => 'Date Uploaded',
        ];
    }

    /**
     * Save user attachment files
     */
    public function upload($path, $files)
    {
        if($this->validate()) {
            foreach ($files as $file) {
                $file->saveAs('uploads/attachments/' . $file->name . '.' . $file->extension);
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * Delete an attachment
     */
    public function deleteAttachment()
    {
        $file = $this->getAttachmentFile();

        // check if file exists on server
        if (empty($file) || !file_exists($file)) {
            return false;
        }
  
        // check if uploaded file can be deleted on server
        if (!unlink($file)) {
            return   false ;
        }
  
        // if deletion successful, delete from database
        //$attachment->

  
        return true;

    }

    /**
     * Get an attachment file
     */
    public function getAttachmentFile()
    {

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAttachment()
    {
        return $this->hasOne(UserAttachment::className(), ['attachment_id' => 'id']);
    }
}
