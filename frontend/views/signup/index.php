<?php

/**
 * @var $signupForm SignupForm
 * @var $city City
 */

use app\models\City;
use frontend\models\SignupForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Регистрация';

?>

<div class="main-container page-container">
    <section class="registration__user">
        <h1>Регистрация аккаунта</h1>
        <div class="registration-wrapper">
            <?php

            $form = ActiveForm::begin([
                'id' => 'TaskForm',
                'options' => ['class' => 'registration__user-form form-create'],
                'errorCssClass' => 'has-error',
                'fieldConfig' => [
                    'options' => ['class' => 'field-container field-container--registration'],
                    'inputOptions' => ['class' => 'input textarea'],
                    'errorOptions' => ['tag' => 'span', 'class' => 'registration__text-error']
                ]
            ]);

            echo $form->field($signupForm, 'email');
            echo $form->field($signupForm, 'name');
            echo $form->field($signupForm, 'city')->dropdownList($city->column(), ['prompt' => '']);
            echo $form->field($signupForm, 'password')->passwordInput();
            echo Html::submitButton('Cоздать аккаунт', ['class' => 'button button__registration']);

            $form = ActiveForm::end();

            ?>
        </div>
    </section>
</div>
