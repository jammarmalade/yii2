<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use backend\models\UserBackend;

/**
 * SignupForm form
 */
class SignupForm extends Model {

    public $username;
    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // 对username的值进行两边去空格过滤
            ['username', 'filter', 'filter' => 'trim'],
            // required表示必须的， message 是username不满足required规则时给的提示消息
            ['username', 'required', 'message' => '用户名不可以为空'],
            // unique表示唯一性，targetClass 表示的数据模型 这里就是说 UserBackend 模型对应的数据表字段username必须唯一
            ['username', 'unique', 'targetClass' => '\backend\models\UserBackend', 'message' => '用户名已存在.'],
            ['username', 'match','pattern'=>'/^[(\x{4E00}-\x{9FA5})a-zA-Z]+[(\x{4E00}-\x{9FA5})a-zA-Z_\d]*$/u','message'=>'用户名由字母，汉字，数字，下划线组成，且不能以数字和下划线开头。'],
            // string 字符串，这里我们限定的意思就是username至少包含 5 个字符，最多 15 个字符
            ['username', 'string', 'min' => 5, 'max' => 16,'tooShort'=>'用户名至少包含五个字符'],
            
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required', 'message' => '邮箱不可以为空'],
            ['email', 'email'],
            ['email', 'string', 'max' => 32],
            ['email', 'unique', 'targetClass' => '\backend\models\UserBackend', 'message' => 'email已存在'],
            ['password', 'required', 'message' => '密码不可以为空'],
            ['password', 'string', 'min' => 6, 'tooShort' => '密码至少填写6位'],
        ];
    }

    /**
     * 用户注册
     *
     * @return true|false 添加成功或者添加失败
     */
    public function signup() {
        // 调用validate方法对表单数据进行验证，验证规则参考上面的rules方法
        if (!$this->validate()) {
            return null;
        }

        // 实现数据入库操作
        $user = new UserBackend();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->time_register = date('Y-m-d H:i:s');

        // 设置密码，密码肯定要加密，暂时我们还没有实现，看下面我们有实现的代码
        $user->setPassword($this->password);

        // 生成 "remember me" 认证key
        $user->generateAuthKey();

        // save(false)的意思是：不调用UserBackend的rules再做校验并实现数据入库操作
        // 这里这个false如果不加，save底层会调用UserBackend的rules方法再对数据进行一次校验，因为我们上面已经调用Signup的rules校验过了，这里就没必要在用UserBackend的rules校验了
        return $user->save(false);
    }

}
