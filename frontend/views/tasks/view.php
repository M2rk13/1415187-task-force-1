<?php

/**
 * @var $task Task
 */

use app\models\Task;
use backend\helpers\BaseHelper;
use yii\helpers\Url;

$this->title = 'Стрница задания ' . $task->name;
$taskCount = $task->customer->getTasksCustomer()->count();

?>

<section class="content-view">
    <div class="content-view__card">
        <div class="content-view__card-wrapper">
            <div class="content-view__header">
                <div class="content-view__headline">
                    <h1><?= $task->name ?></h1>
                    <span>Размещено в категории <a href="browse.html"
                                                   class="link-regular"><?= $task->category->name ?></a> <?= BaseHelper::time_difference(
                            $task->date_add
                        ) ?> назад</span>
                </div>
                <b class="new-task__price new-task__price--clean content-view-price"><?= $task->price ?><b> ₽</b></b>
                <div class="new-task__icon new-task__icon--clean content-view-icon"></div>
            </div>
            <div class="content-view__description">
                <h3 class="content-view__h3">Общее описание</h3>
                <p>
                    <?= $task->description ?>
                </p>
            </div>
            <div class="content-view__attach">
                <h3 class="content-view__h3">Вложения</h3>
                <?php foreach ($task->taskFiles as $file) {
                    $array = explode(DIRECTORY_SEPARATOR, $file->filepath);
                    echo "<a href=\"{$file->filepath}\">{end($array)}</a>";
                } ?>
            </div>
            <div class="content-view__location">
                <h3 class="content-view__h3">Расположение</h3>
                <div class="content-view__location-wrapper">
                    <div class="content-view__map">
                        <a href="#"><img src="/img/map.jpg" width="361" height="292"
                                         alt="Москва, Новый арбат, 23 к. 1"></a>
                    </div>
                    <div class="content-view__address">
                        <span class="address__town"><?= $task->city->name ?></span><br>
                        <span><?= $task->address ?></span>
                        <p><?= $task->address_comment ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-view__action-buttons">
            <button class=" button button__big-color response-button open-modal"
                    type="button" data-for="response-form">Откликнуться
            </button>
            <button class="button button__big-color refusal-button open-modal"
                    type="button" data-for="refuse-form">Отказаться
            </button>
            <button class="button button__big-color request-button open-modal"
                    type="button" data-for="complete-form">Завершить
            </button>
        </div>
    </div>
    <div class="content-view__feedback">
        <h2>Отклики <span>(<?= count($task->responces) ?>)</span></h2>
        <div class="content-view__feedback-wrapper">

            <?php foreach ($task->responces as $response) {
                $rating = round($task->executor->opinionsExecutorRate['rating'], 2); ?>
                <div class="content-view__feedback-card">
                    <div class="feedback-card__top">
                        <a href="<?= Url::to(['users/view', 'id' => $response->executor_id]) ?>">
                            <?php if (!empty($response->executor->avatar)): ?>
                            <img src="<?= $response->executor->avatar ?>" width="55" height="55"></a>
                        <?php endif; ?>
                        <div class="feedback-card__top--name">
                            <p><a href="<?= Url::to(['users/view', 'id' => $response->executor_id]) ?>"
                                  class="link-regular"><?= $response->executor->name ?></a></p>
                            <span <?= $rating < 0.5 ? 'class="star-disabled"' : '' ?>></span><span <?= $rating < 1.5 ? 'class="star-disabled"' : '' ?>></span><span <?= $rating < 2.5 ? 'class="star-disabled"' : '' ?>></span><span <?= $rating < 3.5 ? 'class="star-disabled"' : '' ?>></span><span <?= $rating < 4.5 ? 'class="star-disabled"' : '' ?>></span>
                            <b><?= $rating ?></b>
                        </div>
                        <span class="new-task__time"><?= BaseHelper::time_difference(
                                $response->executor->date_activity
                            ) ?> назад</span>
                    </div>
                    <div class="feedback-card__content">
                        <p>
                            <?= $response->comment ?>
                        </p>
                        <span><?= $response->price ?> ₽</span>
                    </div>
                    <div class="feedback-card__actions">
                        <a class="button__small-color response-button button"
                           type="button">Подтвердить</a>
                        <a class="button__small-color refusal-button button"
                           type="button">Отказать</a>
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>
</section>

<section class="connect-desk">
    <div class="connect-desk__profile-mini">
        <div class="profile-mini__wrapper">
            <h3>Заказчик</h3>
            <div class="profile-mini__top">
                <?php if (!empty($task->customer->avatar)) { ?>
                    <img src="<?= $task->customer->avatar ?>" width="62" height="62" alt="Аватар заказчика">
                <?php } ?>
                <div class="profile-mini__name five-stars__rate">
                    <p><?= $task->customer->name ?></p>
                </div>
            </div>
            <p class="info-customer"><span><?= "{$taskCount} " . BaseHelper::get_noun_plural_form(
                        $taskCount,
                        'tasks'
                    ) ?></span><span class="last-"><?= BaseHelper::time_difference($task->customer->date_add) ?> на сайте</span>
            </p>
            <!--            <a href="/user/view/--><!--" class="link-regular">Смотреть профиль</a>-->
        </div>
    </div>
    <div id="chat-container">
        <!--                    добавьте сюда атрибут task с указанием в нем id текущего задания-->
        <chat class="connect-desk__chat"></chat>
    </div>
</section>
