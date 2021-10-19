<?php

namespace frontend\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

abstract class SecurityController extends Controller
{
    protected const LANDING_CONTROLLER = '/landing';
    protected const TASKS_CONTROLLER = '/tasks';

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,

                'rules' => [
                    [
                        'actions' => ['index', 'view', 'logout'],
                        'allow' => true,
                        'roles' => ['@'],

                    ],
                    [
                        'actions' => ['index', 'view', 'logout'],
                        'allow' => false,
                        'roles' => ['?'],
                        'denyCallback' => function ($rule, $action) {
                            return $action->controller->redirect(self::LANDING_CONTROLLER);
                        }
                    ],
                    [
                        'actions' => ['signup', 'login'],
                        'allow' => true,
                        'roles' => ['?'],

                    ],
                    [
                        'actions' => ['signup', 'login'],
                        'allow' => false,
                        'roles' => ['@'],
                        'denyCallback' => function ($rule, $action) {
                            return $action->controller->redirect(self::TASKS_CONTROLLER);
                        }
                    ],
                ]
            ]
        ];
    }
}