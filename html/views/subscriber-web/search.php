<?php
/** @var yii\web\View $this */
/** @var string $q */
/** @var app\models\Subscriber[] $subscribers */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Поиск абонентов';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin([
    'method' => 'get',
    'action' => ['subscriber-web/search']
]); ?>

<?= Html::textInput('q', $q, ['placeholder' => 'Введите ФИО, адрес или договор']) ?>
<?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end(); ?>

<h3>Результаты</h3>
<ul>
    <?php foreach ($subscribers as $sub): ?>
        <li>
            <?= Html::a($sub->full_name, ['subscriber-web/view', 'id' => $sub->id]) ?>
            (<?= $sub->is_blocked ? 'Заблокирован' : 'Активен' ?>)
        </li>
    <?php endforeach; ?>
</ul>
