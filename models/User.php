<?php

namespace app\models;

class User extends \app\models\ar\User implements \yii\web\IdentityInterface
{
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'hash'], 'required'],
            [['hash'], 'string'],
            [['username', 'access_token', 'auth_key', 'salt'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $auth_key;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        $success = \Yii::$app->getSecurity()->validatePassword($this->salt.$password, $this->hash);
        if($success){
            //登录成功前刷新token
            return $this->updateToken();
        }
    }

    public function updateToken(){        
        $this->auth_key = \Yii::$app->security->generateRandomString();
        $this->access_token = \Yii::$app->security->generateRandomString(); 
        return $this->save();
    }

    public function updatePassword($password){        
        $this->salt = \Yii::$app->security->generateRandomString();       
        $this->hash = \Yii::$app->getSecurity()->generatePasswordHash($this->salt.$password);
        return true;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {   
                $this->updatePassword($this->hash);
            }
            return true;
        }
        return false;
    }
}
