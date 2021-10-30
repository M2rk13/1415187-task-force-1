<?php

namespace frontend\controllers;

use app\models\Category;
use app\models\User;
use frontend\models\TaskCreateForm;
use frontend\models\TasksFilter;
use Yii;
use yii\data\Pagination;
use app\models\Task;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

class TasksController extends SecurityController
{
    private const DEFAULT_TASKS_PAGINATION = 10;

    public function behaviors(): array
    {
        $rules = parent::behaviors();

        $rule = [
            'allow' => false,
            'actions' => ['create'],
            'matchCallback' => function ($rule, $action) {

                if (User::isUserExecutor(\Yii::$app->user->id)) {
                    return $action->controller->redirect(self::TASKS_CONTROLLER);
                }

                return false;
            }
        ];

        array_unshift($rules['access']['rules'], $rule);

        return $rules;
    }

    public function actionIndex(): string
    {
        $filters = new TasksFilter();
        $filters->load(Yii::$app->request->get());
        $tasks = Task::getNewTasks($filters);

        $pagination = new Pagination(
            [
                'defaultPageSize' => self::DEFAULT_TASKS_PAGINATION,
                'totalCount'      => $tasks->count(),
            ]
        );

        $tasks->offset($pagination->offset);
        $tasks->limit($pagination->limit);

        return $this->render(
            'index',
            [
                'tasks'      => $tasks->all(),
                'filters'    => $filters,
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView($id): string
    {

        $task = Task::findOne($id);

        if (empty($task)) {
            throw new NotFoundHttpException('Задание не найдено, проверьте правильность введенных данных', 404);
        }

        return $this->render('view', ['task' => $task]);
    }


    public function actionCreate()
    {
        $taskForm = new TaskCreateForm();

        if (\Yii::$app->request->getIsPost() && \Yii::$app->request->isAjax) {

            $taskForm->load(\Yii::$app->request->post());

            if ($taskForm->validate() && $task = $taskForm->create()) {

                if ($_FILES){
                    return "/tasks/view/{$task->id}";
                }

                return $this->redirect("/tasks/view/{$task->id}");
            }

            \Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($taskForm);
        }

        $allCategories = Category::getCategories();

        return $this->render('create',
            [
                'taskForm' => $taskForm,
                'allCategories' => $allCategories,
            ]);
    }
}
