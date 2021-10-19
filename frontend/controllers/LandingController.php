<?php

namespace frontend\controllers;

use app\models\City;
use app\models\Responce;
use app\models\Status;
use frontend\models\LoginForm;
use frontend\models\SignupForm;
use Yii;
use yii\base\BaseObject;
use yii\web\Controller;
use app\models\Task;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Landing controller
 */
class LandingController extends SecurityController
{
    private const DEFAULT_LAST_TASKS_COUNT = 4;
    private const LOGIN_PAGE_PATH = '../login/index';
    private const SIGNUP_PAGE_PATH = '../signup/index';

    public function actionLogin(): array|string
    {
        $loginForm = new LoginForm();

        if (Yii::$app->request->getIsPost()) {
            $loginForm->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($loginForm);
            }

            if ($loginForm->validate()) {

                $user = $loginForm->getUser();
                Yii::$app->user->login($user);

                $this->homeRedirect();
            }
        }

        $tasks = Task::getLastTasks(self::DEFAULT_LAST_TASKS_COUNT, Status::STATUS_NEW)->all();

        return $this->renderPartial(self::LOGIN_PAGE_PATH,
            [
                'tasks' => $tasks,
                'loginForm' => $loginForm,
            ]);
    }

    public function actionSignup(): string
    {
        $signUp = new SignupForm();

        if ($signUp->load(\Yii::$app->request->post()) && $signUp->validate()) {
            $signUp->signup();

            $this->homeRedirect();
        }

        $city = City::getCities();

        return $this->render(self::SIGNUP_PAGE_PATH, [
            'city' => $city,
            'signupForm' => $signUp,
        ]);
    }

    public function actionLogout(): void
    {
        Yii::$app->user->logout();

        $this->homeRedirect();
    }

    public function homeRedirect(): Response
    {
        return $this->goHome();
    }
}