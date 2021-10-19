<?php

namespace frontend\models;

use app\models\User;
use yii\base\Model;

class LoginForm extends Model
{
    public $email;
    public $password;
    private $user;

    public const LOGIN_FORM_ERROR = 'Неправильный email или пароль';

    public function attributeLabels(): array
    {
        return [
            'email' => 'Электронная почта',
            'password' => 'Пароль',
        ];
    }

    public function rules(): array
    {
        return [

            [['email', 'password'], 'safe'],
            [['email'], 'required', 'message' => 'Email не может быть пустым'],
            [['password'], 'required', 'message' => 'Пароль не может быть пустым'],

            ['email', 'trim'],
            ['email', 'email'],
            ['password', 'string', 'min' => \Yii::$app->params['user.passwordMinLength'], 'tooShort' => 'Длина пароля должна быть от ' . User::MAX_PASSWORD_LENGTH . ' символов'],
            ['password', 'string', 'max' => User::MAX_STRING_LENGTH, 'tooLong' => 'Длина пароля должна быть до ' . User::MAX_STRING_LENGTH . ' символов'],
            ['password', 'validatePassword']
        ];
    }

    public function validatePassword($attribute): void
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, );
            }
        }
    }

    public function getUser(): ?User
    {
        if ($this->user === null) {
            $this->user = User::findOne(['email' => $this->email]);
        }

        return $this->user;
    }
}