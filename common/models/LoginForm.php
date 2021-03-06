<?php
namespace common\models;

use Yii;
use yii\base\Model;
use backend\models\UserBackend as User;
/**
 * 对表单数据进行验证的rule
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username和password必须
            ['username', 'required', 'message' => '用户名不可以为空'],
            ['password', 'required', 'message' => '密码不能为空'],
            ['username', 'string', 'min' => 5, 'max' => 16,'tooShort'=>'用户名至少包含五个字符'],
            // rememberMe是一个boolean值
            ['rememberMe', 'boolean'],
            // 这里需要注意的是 validatePassword 是自定义的验证方法！！！只需要在当前模型内增加对应的认证方法即可
            ['password', 'validatePassword'],
        ];
    }

    /**
     * 自定义的密码认证方法
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        // hasErrors方法，用于获取rule失败的数据
        if (!$this->hasErrors()) {
            // 调用当前模型的getUser方法获取用户
            $user = $this->getUser();
            // 获取到用户信息，然后校验用户的密码对不对，校验密码调用的是 backend\models\UserBackend 的 validatePassword 方法，
            // 在 UserBackend 方法里增加
            if (!$user || !$user->validatePassword($this->password)) {
                // 验证失败，调用addError方法给用户提醒信息
                $this->addError($attribute, '用户名或密码错误.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        // 调用validate方法 进行rule的校验，其中包括用户是否存在和密码是否正确的校验
        if ($this->validate()) {
            // 校验成功后，session保存用户信息
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *根据用户名获取用户的认证信息
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            // 根据用户名 调用认证类 backend\models\UserBackend 的 findByUsername 获取用户认证信息
            // 在UserBackend增加一个findByUsername方法对其实现
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
