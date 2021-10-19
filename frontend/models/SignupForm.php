<?php

namespace frontend\models;

use Yii;

use app\models\User;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $name;
    public $email;
    public $city;
    public $password;

    public function attributeLabels(): array
    {
        return [
            'email' => 'Электронная почта',
            'name' => 'Ваше имя',
            'city' => 'Город проживания',
            'password' => 'Пароль'
        ];
    }

    public function rules(): array
    {
        return [

            [['email', 'name', 'city', 'password'], 'safe'],
            [['email'], 'required', 'message' => 'Введите Ваш адрес электронной почты'],
            [['name'], 'required', 'message' => 'Введите Ваши имя и фамилию'],
            [['city'], 'required', 'message' => 'Укажите город, чтобы находить подходящие задачи'],
            [['password'], 'required', 'message' => 'Введите Ваш пароль'],

            ['email', 'trim'],
            ['email', 'email', 'message' => 'Введите валидный адрес электронной почты'],
            ['email', 'string', 'max' => User::MAX_STRING_LENGTH],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Пользователь с таким email уже существует'],

            ['name', 'string', 'max' => self::MAX_STRING_LENGTH],

            ['password', 'string', 'min' => \Yii::$app->params['user.passwordMinLength'], 'tooShort' => 'Длина пароля от 8 символов до ' . User::MAX_PASSWORD_LENGTH . ' символов'],
            ['password', 'string', 'max' => User::MAX_PASSWORD_LENGTH, 'tooLong' => 'Длина пароля от 8 символов до ' . User::MAX_PASSWORD_LENGTH . ' символов'],
        ];
    }

    public function signup(): ?bool
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->city_id = $this->city;
        $user->password = Yii::$app->security->generatePasswordHash($this->password);

        return $user->save();
    }

    protected function sendEmail($user): bool
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
