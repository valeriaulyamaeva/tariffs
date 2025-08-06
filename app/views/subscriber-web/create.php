<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\MaskedInput;

/** @var yii\web\View $this */
/** @var app\models\Subscriber $model */
/** @var app\models\Tariff[] $tariffs */

$this->title = 'Создание абонента';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'full_name')->textInput(['placeholder' => 'ФИО'])->label(false) ?>

<?= $form->field($model, 'city_code')->dropDownList([
    '17' => 'Минск (17)',
    '25' => 'Гомель (25)',
    '29' => 'Брест (29)',
    '33' => 'Гродно (33)',
    '44' => 'Могилев (44)',
], ['prompt' => 'Выберите город'])->label(false) ?>

<?= $form->field($model, 'contract')->textInput(['placeholder' => 'Последние 6 цифр договора'])->label(false) ?>

<?= $form->field($model, 'phone')->widget(MaskedInput::class, [
    'mask' => '+375999999999',
    'options' => [
        'placeholder' => 'Телефон',
        'class' => 'form-control',
    ],
])->label(false) ?>


<?= $form->field($model, 'address')->textInput(['placeholder' => 'Адрес проживания'])->label(false) ?>

<?= $form->field($model, 'tariff_id')->dropDownList(ArrayHelper::map($tariffs, 'id', 'name'), ['prompt' => 'Выберите тариф'])->label(false) ?>

<!-- поле блокировки удалено -->

<div class="form-group">
    <?= Html::submitButton('Создать', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>

