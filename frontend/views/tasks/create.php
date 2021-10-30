<?php

use app\models\Category;
use frontend\models\TaskCreateForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $taskForm TaskCreateForm
 * @var $allCategories Category
 */
$this->title = 'Создание задания';
?>
<section class="create__task">
    <h1><?= $this->title ?></h1>
    <div class="create__task-main">

        <?php
        $form = ActiveForm::begin([
            'action' => ['tasks/create'],
            'method' => 'post',
            'options' => [
                'class' => 'create__task-form form-create',
                'enctype' => 'multipart/form-data',
                'id' => 'task-form',
            ],
            'fieldConfig' => [
                'options' => [
                    'tag' => false,
                ],
            ],
        ]);

        echo $form->field($taskForm, 'title')->textarea(['class' => 'input textarea', 'rows' => 1, 'placeholder' => 'Повесить полку']);
        ?>

        <span>Кратко опишите суть работы</span>
        <?= $form->field($taskForm, 'description')->textarea(['class' => 'input textarea', 'rows' => 7, 'placeholder' => 'Расскажите подробнее о задании']) ?>
        <span>Укажите все пожелания и детали, чтобы исполнителям было проще соориентироваться</span>
        <?= $form->field($taskForm, 'category')->dropdownList($allCategories, ['class' => 'multiple-select input multiple-select-big', 'size' => 1, 'prompt' => 'Выберите категорию']) ?>
        <?= Html::activeLabel($taskForm, 'files') ?>
        <span>Загрузите файлы, которые помогут исполнителю лучше выполнить или оценить работу</span>
        <div class="create__file">
            <?= Html::activeFileInput($taskForm, 'files[]', ['class' => 'dropzone', 'style' => 'height: 100%; width: 100%; opacity: 0']) ?>
            <span style="position: absolute">Добавить новый файл</span>
        </div>
        <?= $form->field($taskForm, 'address')->textInput(['class' => 'input-navigation input-middle input', 'type' => 'search']) ?>
        <span>Укажите адрес исполнения, если задание требует присутствия</span>
        <div class="create__price-time">
            <div class="create__price-time--wrapper">
                <?= $form->field($taskForm, 'price')->textarea(['class' => 'input textarea input-money', 'rows' => 1]) ?>
                <span>Не заполняйте для оценки исполнителем</span>
            </div>
            <div class="create__price-time--wrapper">
                <?= $form->field($taskForm, 'expire')->textInput(['class' => 'input-middle input input-date', 'type' => 'date']) ?>
                <span>Укажите крайний срок исполнения</span>
            </div>
        </div>

        <!--        --><?php
        //        $form->errorSummaryCssClass();
        //
        //        ?>

        <?= $form->errorSummary($taskForm,
            [
                'header' => '<div class="warning-item warning-item--error">
                            <h2>Ошибки заполнения формы</h2>',
                'footer' => '</div>'
            ]) ?>


        <?php file_put_contents('../../tester.txt', Html::errorSummary($taskForm)) ?>

<!--        <div class="warning-item warning-item--error">-->
<!--            <h2>Ошибки заполнения формы</h2>-->
<!--            <h3>Категория</h3>-->
<!--            <p>Это поле должно быть выбрано.<br>-->
<!--                Задание должно принадлежать одной из категорий</p>-->
<!--        </div>-->

        <?php

        echo Html::submitButton('Опубликовать', ['class' => 'button']);
        ActiveForm::end();

        ?>

        <div class="create__warnings">
            <div class="warning-item warning-item--advice">
                <h2>Правила хорошего описания</h2>
                <h3>Подробности</h3>
                <p>Друзья, не используйте случайный<br>
                    контент – ни наш, ни чей-либо еще. Заполняйте свои
                    макеты, вайрфреймы, мокапы и прототипы реальным
                    содержимым.</p>
                <h3>Файлы</h3>
                <p>Если загружаете фотографии объекта, то убедитесь,
                    что всё в фокусе, а фото показывает объект со всех
                    ракурсов.</p>
            </div>
        </div>
    </div>
</section>
