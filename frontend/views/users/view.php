<?php

/**
 * @var $user                 User
 * @var $rate                 array
 * @var $taskCount            int
 * @var $feedbacks            array
 * @var $portfolios           array
 * @var $paginationPhoto      Pagination
 * @var $paginationComment    Pagination
 */

use app\models\Task;
use app\models\User;
use backend\helpers\BaseHelper;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$rating = round($rate['rate'], 2);

?>

<section class="content-view">
    <div class="user__card-wrapper">
        <div class="user__card">
            <?php if (!empty($user->avatar)) { ?>
            <img src="<?= $user->avatar ?>" width="120" height="120" alt="Аватар пользователя">
            <?php } ?>
            <div class="content-view__headline">
                <h1><?= $user->name ?></h1>
                <p>Россия, <?= $user->city->name ?>, <?= BaseHelper::time_difference($user->birthday) ?></p>
                <div class="profile-mini__name five-stars__rate">
                    <span <?= $rating < 0.5 ? 'class="star-disabled"' : '' ?>></span><span <?= $rating < 1.5 ? 'class="star-disabled"' : '' ?>></span><span <?= $rating < 2.5 ? 'class="star-disabled"' : '' ?>></span><span <?= $rating < 3.5 ? 'class="star-disabled"' : '' ?>></span><span <?= $rating < 4.5 ? 'class="star-disabled"' : '' ?>></span>
                    <b><?= $rating ?></b>
                </div>
                <b class="done-task">Выполнил <?= "{$taskCount} " . BaseHelper::get_noun_plural_form($taskCount, 'tasks') ?></b><b
                        class="done-review">Получил <?= "{$rate['count']} " . BaseHelper::get_noun_plural_form(
                        $rate['count'],
                        'feedbacks'
                    ) ?></b>
            </div>
            <div class="content-view__headline user__card-bookmark user__card-bookmark--current">
                <span>Был на сайте <?= BaseHelper::time_difference($user->date_activity) ?> назад</span>
                <a href="#"><b></b></a>
            </div>
        </div>
        <div class="content-view__description">
            <p><?= $user->about ?></p>
        </div>
        <div class="user__card-general-information">
            <div class="user__card-info">
                <h3 class="content-view__h3">Специализации</h3>
                <div class="link-specialization">
                    <?php foreach ($user->specialisation as $spec) { ?>
                        <a href="browse.html" class="link-regular"><?= $spec->category->name ?></a>
                    <?php } ?>
                </div>
                <h3 class="content-view__h3">Контакты</h3>
                <div class="user__card-link">
                    <a class="user__card-link--tel link-regular" href="tel:+<?= $user->phone ?>"><?= $user->phone ?></a>
                    <a class="user__card-link--email link-regular" href="mailto:<?= $user->email ?>"><?= $user->email ?></a>
                    <a class="user__card-link--skype link-regular" href="skype:+<?= $user->skype ?>"><?= $user->name ?></a>
                </div>
            </div>
            <div class="user__card-photo">
                <h3 class="content-view__h3">Фото работ</h3>
                <?php foreach ($portfolios as $portfolio) { ?>
                <a href="<?= $portfolio['filepath'] ?>"><img src="<?= $portfolio['filepath'] ?>" width="85" height="86" alt="Фото работы"></a>
                <?php } ?>
                <div class="new-task__pagination">

                    <?= LinkPager::widget(
                        [
                            'pagination'         => $paginationPhoto,
                            'options'            => [
                                'class' => 'new-task__pagination-list',
                            ],
                            'activePageCssClass' => 'pagination__item--current',
                            'pageCssClass'       => 'pagination__item',
                            'prevPageCssClass'   => 'pagination__item',
                            'nextPageCssClass'   => 'pagination__item',
                            'nextPageLabel'      => '⠀',
                            'prevPageLabel'      => '⠀',
                            'hideOnSinglePage'   => true
                        ]
                    ) ?>

                </div>
            </div>
        </div>
    </div>
    <div class="content-view__feedback">
        <h2>Отзывы<span>(<?= $rate['count'] ?>)</span></h2>
        <div class="content-view__feedback-wrapper reviews-wrapper">
            <?php foreach ($feedbacks as $feedback) {
                $task = Task::findOne($feedback['task_id']);
                $customer = User::findOne($feedback['customer_id']); ?>
                <div class="feedback-card__reviews">
                    <p class="link-task link"><?= (!empty($task)) ? "Задание <a href=\" " . Url::to(
                                ['tasks/view', 'id' => $task->id]
                            ) . "\" class=\"link-regular\">«{$task->name}»</a>" : '' ?></p>
                    <div class="card__review">
                        <?php if (!empty($customer->avatar)) { ?>
                            <a href="<?= $customer->avatar ?>"><img src="<?= $customer->avatar ?>" width="55" height="54" alt="Аватар пользователя"></a>
                        <?php } ?>
                        <div class="feedback-card__reviews-content">
                            <p class="link-name link"><!--<a href="/user/view/<?php //$customer->id ?>" class="link-regular"> --><?= $customer->name ?></a></p>
                            <p class="review-text">
                                <?= $feedback['description'] ?>
                            </p>
                        </div>
                        <div class="card__review-rate">
                            <p class="<?= $feedback['rate'] > 3 ? 'five-rate' : 'three-rate' ?> big-rate"><?= $feedback['rate'] ?><span></span></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="new-task__pagination">

            <?= LinkPager::widget(
                [
                    'pagination'         => $paginationComment,
                    'options'            => [
                        'class' => 'new-task__pagination-list',
                    ],
                    'activePageCssClass' => 'pagination__item--current',
                    'pageCssClass'       => 'pagination__item',
                    'prevPageCssClass'   => 'pagination__item',
                    'nextPageCssClass'   => 'pagination__item',
                    'nextPageLabel'      => '⠀',
                    'prevPageLabel'      => '⠀',
                    'hideOnSinglePage'   => true
                ]
            ) ?>

        </div>
    </div>
</section>
