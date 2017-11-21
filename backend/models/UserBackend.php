<?php

namespace backend\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $password_reset_token
 * @property string $email
 * @property integer $notice
 * @property string $group_id
 * @property string $time_login
 * @property string $time_register
 * @property integer $status
 */
class UserBackend extends \yii\db\ActiveRecord implements IdentityInterface {

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    //注册开始时间
    public $time_register_form;
    //注册结束时间
    public $time_register_to;
    public $password1;
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['username', 'group_id', 'time_login', 'time_register', 'status'], 'required'],
            [['notice', 'group_id', 'status', 'records'], 'integer'],
            [['time_login', 'time_register'], 'safe'],
            [['username'], 'string', 'max' => 15],
            [['password', 'email'], 'string', 'max' => 32],
            ['password1', 'compare', 'compareAttribute' => 'password','message'=>'两次输入的密码不一致！'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => '用户id',
            'username' => '用户名',
            'password' => '密码',
            'password_reset_token' => '重置密码token',
            'password1' => '确认密码',
            'email' => '邮箱',
            'auth_key' => '记住我的认证key',
            'notice' => '提醒数',
            'records' => '记录数',
            'group_id' => '用户组id',
            'time_login' => '最后登录时间',
            'time_register' => '注册时间',
            'status' => '用户状态，0删除，1正常',
        ];
    }

    /**
     * @inheritdoc
     * 根据user_backend表的主键（id）获取用户
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     * 根据access_token获取用户
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @inheritdoc
     * 获取用户身份关联数据表的主键 ,用以标识 Yii::$app->user->id 的返回值
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     * 获取auth_key的方法
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     * 验证auth_key的方法
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * 为model的password字段生成密码的hash值
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * 生成 "remember me" 认证key
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * 根据username获取用户
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username]);
    }

    /**
     * 验证密码的准确性
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password);
    }
    /**
     * 获取重置密码的token
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    /**
     * 验证重置密码token 的时效性，是否过期
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }
    /**
     * 删除重置密码token
     */
    public function removePasswordResetToken(){
        $this->password_reset_token = null;
    }
    /**
     * 根据token查找用户
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token){
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

}
