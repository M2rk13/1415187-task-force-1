<?php

namespace frontend\controllers;

use frontend\models\UsersFilter;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use app\models\User;
use yii\web\NotFoundHttpException;

class UsersController extends Controller
{
    private const DEFAULT_PORTFOLIO_PAGINATION = 3;
    private const DEFAULT_COMMENT_PAGINATION = 5;

    public function actionIndex(): string
    {
        $filters = new UsersFilter();
        $filters->load(Yii::$app->request->get());

        $users = User::getExecutors($filters);

        $pagination = new Pagination(
            [
                'defaultPageSize' => 10,
                'totalCount'      => $users->count(),
            ]
        );

        $users->offset($pagination->offset)
            ->limit($pagination->limit);

        return $this->render(
            'index',
            [
                'title' => '',
                'users'      => $users->all(),
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
        $user = User::findOne($id);

        if (empty($user->specialisations ?? null)) {
            $user = null;
        }

        if (!$user) {
            throw new NotFoundHttpException('Исполнитель не найден, проверьте правильность введенных данных', 404);
        }

        $rate = User::getAllExecutorRate($id);
        $taskCount = count($user->getTasksExecutor()->all());

        $paginationPhoto = new Pagination(
            [
                'defaultPageSize' => self::DEFAULT_PORTFOLIO_PAGINATION,
                'totalCount'      => count($user->portfolios),
            ]
        );

        $paginationComment = new Pagination(
            [
                'defaultPageSize' => self::DEFAULT_COMMENT_PAGINATION,
                'totalCount'      => $rate['count'],
            ]
        );

        $feedbacks = User::getExecutorOpinionsAndFeedbacks($user->id, $paginationComment);
        $portfolios = $user->getPortfolios($paginationPhoto);

        return $this->render('view', [
            'user' => $user,
            'rate' => $rate,
            'taskCount' => $taskCount,
            'feedbacks' => $feedbacks->all(),
            'portfolios' => $portfolios->all(),
            'paginationPhoto' => $paginationPhoto,
            'paginationComment' => $paginationComment,
        ]);
    }
}
