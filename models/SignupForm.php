<?php

namespace app\models;


use floor12\phone\PhoneValidator;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user This property is read-only.
 *
 */
class SignupForm extends Model
{
    public $email;
    public $fio;
    public $phone;
    public $password;
    public $password2;
    public $rememberMe = true;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['email', 'fio', 'password', 'password2'], 'required'],
            [['email'], 'email'],
            [['email'], 'unique', 'targetClass' => 'app\models\User', 'targetAttribute' => 'email'],
            [['fio'], 'string', 'min' => 5],
            [['phone'], PhoneValidator::class],
            // password is validated by validatePassword()
            //[['password'], 'validatePassword'],
            [['password'], 'string', 'min' => 8],
            [['password2'], 'compare', 'compareAttribute' => 'password', 'message' => "Passwords don't match"],
            // rememberMe must be a boolean value
            [['rememberMe'], 'boolean'],
        ];
    }

    public function signup()
    {
        if ($this->validate()) {
            return $this->createUser();
        }
        return false;
    }

    /**
     * @throws \yii\base\Exception
     */
    public function createUser()
    {
        $user = new User();
        $user->attributes = $this->attributes;
        $user->setPassword($this->password);
        $user->create();
        return $user;
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }
}
