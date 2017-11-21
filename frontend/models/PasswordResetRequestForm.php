<?php

namespace frontend\models;

use backend\models\UserBackend as User;
use yii\base\Model;
use Yii;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model {

    public $email;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => '邮箱不存在！'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail() {
        /* @var $user User */
        $user = User::findOne([
                'status' => User::STATUS_ACTIVE,
                'email' => $this->email,
        ]);

        if ($user) {
            if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }

            if ($user->save(false)) {
                $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/reset-password', 'token' => $user->password_reset_token]);
                $content = 'Hello，' . $user->username . '：<br/>&nbsp;&nbsp;请点击下面的链接来完成重置密码<br/><br/>';
                $content .= '<a href="' . $resetLink . '">重置密码</a>';
                return Yii::$app->mailer->compose()
                        ->setFrom([Yii::$app->params['supportEmail'] => 'jam00'])
                        ->setTo($this->email)
                        ->setSubject('[重置密码] jam00的博客')
                        ->setHtmlBody($content)
                        ->send();
            }
        }

        return false;
    }

}
