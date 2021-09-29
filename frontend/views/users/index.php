<?php
/**
 * @var $this       yii\web\View
 * @var $users      UsersController
 * @var $filters    UsersFilter
 * @var $pagination Pagination
 */

use app\models\Category;
use app\models\User;
use backend\helpers\BaseHelper;
use frontend\controllers\UsersController;
use frontend\models\UsersFilter;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

?>

<section class="user__search">
    <?php foreach ($users as $user) {
        $taskCount = count($user->completedTasksExecutor);
        $rate = User::getAllExecutorRate($user->id);
        $rating = round($rate['rate'], 2); ?>
        <div class="content-view__feedback-card user__search-wrapper">
            <div class="feedback-card__top">
                <div class="user__search-icon">
                    <a href="<?= Url::to(
                        ['users/view', 'id' => $user->id]
                    ) ?>"><?= !empty($user->avatar) ? "<img src=\"<?= $user->avatar ?>\" width=\"65\" height=\"65\">" : ''; ?></a>
                    <span><?= "{$taskCount} " . BaseHelper::get_noun_plural_form($taskCount, 'tasks') ?></span>
                    <span><?= "{$rate['count']} " . BaseHelper::get_noun_plural_form(
                            $rate['count'],
                            'feedbacks'
                        ) ?></span>
                </div>
                <div class="feedback-card__top--name user__search-card">
                    <p class="link-name"><a href="<?= Url::to(['users/view', 'id' => $user->id]) ?>"
                                            class="link-regular"><?= $user->name ?></a></p>
                    <span <?= $rating < 0.5 ? 'class="star-disabled"' : '' ?>></span><span <?= $rating < 1.5 ? 'class="star-disabled"' : '' ?>></span><span <?= $rating < 2.5 ? 'class="star-disabled"' : '' ?>></span><span <?= $rating < 3.5 ? 'class="star-disabled"' : '' ?>></span><span <?= $rating < 4.5 ? 'class="star-disabled"' : '' ?>></span>
                    <b><?= $rating ?></b>
                    <p class="user__search-content">
                        <?= $user->about ?>
                    </p>
                </div>
                <span class="new-task__time">Был на сайте <?= BaseHelper::time_difference(
                        $user->date_activity
                    ) ?> назад</span>
            </div>
            <div class="link-specialization user__search-link--bottom">
                <?php foreach ($user->specialisation as $spec) { ?>
                    <a href="browse.html" class="link-regular"><?= $spec->category->name ?></a>
                <?php } ?>
            </div>
        </div>
    <?php } ?>

    <div class="new-task__pagination">

        <?= LinkPager::widget(
            [
                'pagination'         => $pagination,
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

</section>

<section class="search-task">
    <div class="search-task__wrapper">

        <?php

        $form = ActiveForm::begin(
            [
                'id'      => 'filter-form',
                'options' => ['class' => 'search-task__form'],
                'method'  => 'get'
            ]
        );

        ?>

        <fieldset class="search-task__categories">
            <legend>Категории</legend>

            <?php echo $form->field(
                $filters,
                'categories',
                [
                    'template'     => '{input}',
                    'labelOptions' => ['class' => 'checkbox__legend']
                ]
            )->checkboxList(
                Category::getCategories(),
                [
                    'item' => function ($index, $label, $name, $checked, $value) {
                        $chek = $checked ? 'checked' : "";
                        return "<label class=\"checkbox__legend\">
                                <input class=\"visually-hidden checkbox__input\" type=\"checkbox\" name=\"{$name}\" value=\"{$value}\" {$chek}>
                                <span>{$label}</span>
                            </label>";
                    },
                ]
            ) ?>

        </fieldset>


        <fieldset class="search-task__categories">
            <legend>Дополнительно</legend>
            <?php

            echo $form->field(
                $filters,
                'vacant',
                [
                    'template' => '{input}{label}',
                    'options'  => ['class' => ''],
                ]
            )
                ->checkbox(
                    [
                        'class'        => 'checkbox__input visually-hidden',
                        'uncheck'      => false,
                        'label'        => "<span>{$filters->attributeLabels()['vacant']}</span>",
                        'labelOptions' => ['class' => 'checkbox__legend']
                    ],
                    true
                );

            echo $form->field(
                $filters,
                'online',
                [
                    'template' => '{input}{label}',
                    'options'  => ['class' => ''],
                ]
            )
                ->checkbox(
                    [
                        'class'        => 'checkbox__input visually-hidden',
                        'uncheck'      => false,
                        'label'        => "<span>{$filters->attributeLabels()['online']}</span>",
                        'labelOptions' => ['class' => 'checkbox__legend']
                    ],
                    true
                );

            echo $form->field(
                $filters,
                'hasFeedback',
                [
                    'template' => '{input}{label}',
                    'options'  => ['class' => ''],
                ]
            )
                ->checkbox(
                    [
                        'class'        => 'checkbox__input visually-hidden',
                        'uncheck'      => false,
                        'label'        => "<span>{$filters->attributeLabels()['hasFeedback']}</span>",
                        'labelOptions' => ['class' => 'checkbox__legend']
                    ],
                    true
                );

            echo $form->field(
                $filters,
                'inFavorites',
                [
                    'template' => '{input}{label}',
                    'options'  => ['class' => ''],
                ]
            )
                ->checkbox(
                    [
                        'class'        => 'checkbox__input visually-hidden',
                        'uncheck'      => false,
                        'label'        => "<span>{$filters->attributeLabels()['inFavorites']}</span>",
                        'labelOptions' => ['class' => 'checkbox__legend']
                    ],
                    true
                );

            ?>

        </fieldset>

        <?php

        echo $form->field(
            $filters,
            'search',
            [
                'template'     => '{label}{input}',
                'options'      => ['class' => ''],
                'labelOptions' => ['class' => 'search-task__name']
            ]
        )
            ->input(
                'search',
                [
                    'class' => 'input-middle input',
                    'style' => 'width: 100%'
                ]
            );

        echo Html::submitButton('Искать', ['class' => 'button']);

        $form = ActiveForm::end();

        ?>

    </div>
</section>
