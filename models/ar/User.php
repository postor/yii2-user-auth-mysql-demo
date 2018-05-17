<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $hash
 * @property string $access_token
 * @property string $auth_key
 * @property string $salt
 */
class User extends \yii\db\ActiveRecord
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
            [['username', 'hash', 'access_token', 'auth_key', 'salt'], 'required'],
            [['hash'], 'string'],
            [['username', 'access_token', 'auth_key', 'salt'], 'string', 'max' => 32],
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
            'hash' => 'Hash',
            'access_token' => 'Access Token',
            'auth_key' => 'Auth Key',
            'salt' => 'Salt',
        ];
    }
}
