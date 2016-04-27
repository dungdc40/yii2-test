<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $is_admin
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at'], 'required'],
            [['is_admin', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'is_admin' => 'Is Admin',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
	
	/*
	 * save role checkboxes sent from users
	 */
	public static function saveRoles($data)
	{	
		$processedData = static::processRoleData($data);
		
		if(isset($processedData['isAdmin'])) {
			foreach($processedData['isAdmin'] as $index => $value) {
				$query = 'UPDATE user SET is_admin=:is_admin WHERE id=:id';
				Yii::$app->db->createCommand($query)
							->bindValues([':is_admin' => $value, ':id' => $index])
							->execute();
			}
		}	
	}
	
	public static function processRoleData($data)
	{
		
		if(isset($data['isAdminHidden'])) {
			/* comapre hidden fields and checkboxes fields to see what have been changed,
			* $data['daily-noti'] will contain the changes */
			foreach($data['isAdminHidden'] as $index => $isAdminHidden) {
				// if field appear in hidden field and checkbox field, this mean the former
				// state of checkbox is checked and it has not been changed
				if($isAdminHidden && isset($data['isAdmin'][$index])) {
					unset($data['isAdmin'][$index]);
				} else if($isAdminHidden && !isset($data['isAdmin'][$index])) {
					// checkbox has been unchecked
					$data['isAdmin'][$index] = 0;
				} else if(!$isAdminHidden && isset($data['isAdmin'][$index])) {
					// checkbox has been checked
					$data['isAdmin'][$index] = 1;
				}
			}
			unset($data['isAdminHidden']);			
		}
		return $data;
	}
}
