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

    private const MAX_STRING_LENGTH = 128;
    private const MAX_PASSWORD_LENGTH = 16;

    public function attributeLabels()
    {
        return [
            'email' => 'Электронная почта',
            'name' => 'Ваше имя',
            'city' => 'Город проживания',
            'password' => 'Пароль'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['email', 'name', 'city', 'password'], 'safe'],
            [['email'], 'required', 'message' => 'Введите Ваш адрес электронной почты'],
            [['name'], 'required', 'message' => 'Введите Ваши имя и фамилию'],
            [['city'], 'required', 'message' => 'Укажите город, чтобы находить подходящие задачи'],
            [['password'], 'required', 'message' => 'Введите Ваш пароль'],

            ['email', 'trim'],
            ['email', 'email', 'message' => 'Введите валидный адрес электронной почты'],
            ['email', 'string', 'max' => self::MAX_STRING_LENGTH],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Пользователь с таким email уже существует'],

            ['name', 'string', 'max' => self::MAX_STRING_LENGTH],

            ['password', 'string', 'min' => \Yii::$app->params['user.passwordMinLength'], 'message' => 'Длина пароля от 8 символов до ' . self::MAX_PASSWORD_LENGTH . ' символов'],
            ['password', 'string', 'max' => self::MAX_PASSWORD_LENGTH, 'message' => 'Длина пароля от 8 символов до ' . self::MAX_PASSWORD_LENGTH . ' символов'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
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

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
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
