<?php

namespace frontend\controllers;

use app\models\Status;
use yii\web\Controller;
use app\models\Task;

/**
 * Landing controller
 */
class LandingController extends Controller
{
    private const DEFAULT_LAST_TASKS_COUNT = 4;

    public function actionIndex(): string
    {
        $tasks = Task::getLastTasks(self::DEFAULT_LAST_TASKS_COUNT, Status::STATUS_NEW)->all();

        return $this->renderPartial('index', ['tasks' => $tasks]);
    }
}
