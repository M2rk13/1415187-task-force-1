<?php

use backend\helpers\BaseHelper;
use frontend\controllers\TasksController;
use frontend\models\TasksFilter;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use app\models\Category;

/**
 * @var $tasks      TasksController
 * @var $pagination Pagination
 * @var $filters    TasksFilter
 */

$this->title = 'Задания';

?>

<section class="new-task">
    <div class="new-task__wrapper">
        <h1>Новые задания</h1>

        <?php foreach ($tasks as $task) { ?>
            <div class="new-task__card">
                <div class="new-task__title">
                    <a href="<?= Url::to(['tasks/view', 'id' => $task->id]) ?>" class="link-regular"><h2><?= $task->name ?></h2></a>
                    <a class="new-task__type link-regular" href="#"><p><?= $task->category->name ?></p></a>
                </div>
                <div class="new-task__icon new-task__icon--<?= $task->category->icon ?>"></div>
                <p class="new-task_description">
                    <?= $task->description ?>
                </p>
                <b class="new-task__price new-task__price--<?= $task->category->icon ?>"><?= $task->price ?> <b>
                        ₽</b></b>
                <p class="new-task__place"><?= Html::encode(
                        "{$task->city->name}, {$task->address}, {$task->city->lat}-{$task->city->long}"
                    ) ?></p>
                <span class="new-task__time"><?= BaseHelper::time_difference($task->date_add) ?> назад</span>
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
                        $chek = $checked ? 'checked' : '';
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
                'noExecutor',
                [
                    'template' => '{input}{label}',
                    'options'  => ['class' => ''],
                ]
            )
                ->checkbox(
                    [
                        'class'        => 'checkbox__input visually-hidden',
                        'uncheck'      => false,
                        'label'        => "<span>{$filters->attributeLabels()['noExecutor']}</span>",
                        'labelOptions' => ['class' => 'checkbox__legend']
                    ],
                    true
                );

            echo $form->field(
                $filters,
                'remoteWork',
                [
                    'template' => '{input}{label}',
                    'options'  => ['class' => ''],
                ]
            )
                ->checkbox(
                    [
                        'class'        => 'checkbox__input visually-hidden',
                        'uncheck'      => false,
                        'label'        => "<span>{$filters->attributeLabels()['remoteWork']}</span>",
                        'labelOptions' => ['class' => 'checkbox__legend']
                    ],
                    true
                );

            ?>

        </fieldset>

        <?php

        echo $form->field(
            $filters,
            'period',
            [
                'template'     => '{label}{input}',
                'options'      => ['class' => ''],
                'labelOptions' => ['class' => 'search-task__name']
            ]
        )
            ->dropDownList(
                [
                    '1 day'   => 'За день',
                    '1 week'  => 'За неделю',
                    '1 month' => 'За месяц',
                ],
                [
                    'class'  => 'multiple-select input',
                    'style'  => 'width: 100%',
                    'prompt' => 'Выберите период'
                ]
            );

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

        ActiveForm::end();

        ?>

    </div>
</section>
