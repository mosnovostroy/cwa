<?php
namespace frontend\models;

use yii\base\Model;
use yii\base\InvalidParamException;
use common\models\User;

/**
 * Password reset form
 */
class SignupConfirm extends Model
{
    /**
     * @var \common\models\User
     */
    private $_user;


    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Signup confirm token cannot be blank.');
        }
        $this->_user = User::findBySignupConfirmToken($token);
        if (!$this->_user) {
            throw new InvalidParamException('Wrong signup confirm token.');
        }
        parent::__construct($config);
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function signupConfirm()
    {
        $user = $this->_user;
        $user->status = User::STATUS_ACTIVE;
        $user->removePasswordResetToken();

        return $user->save(false);
    }
}
