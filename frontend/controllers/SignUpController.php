<?php

namespace frontend\controllers;

use app\models\City;
use frontend\models\SignupForm;
use yii\web\Controller;

class SignupController extends Controller
{
    public function actionIndex()
    {
        $signUp = new SignupForm();

        if ($signUp->load(\Yii::$app->request->post()) && $signUp->validate()) {
            $signUp->signup();
            return $this->goHome();
        }

        $city = City::getCities();

        return $this->render('index', [
            'city' => $city,
            'signupForm' => $signUp,
        ]);
    }
}
