<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Subscriber $model */
/** @var app\models\Tariff[] $tariffs */


$this->title = 'Карточка абонента';
?>

<h1><?= Html::encode($this->title) ?></h1>

<ul>
    <li>ФИО: <?= Html::encode($model->full_name) ?></li>
    <li>Договор: <?= Html::encode($model->contract_number) ?></li>
    <li>Телефон: <?= Html::encode($model->phone) ?></li>
    <li>Адрес: <?= Html::encode($model->address) ?></li>
    <li>Тариф: <?= Html::encode($model->tariff->name ?? '—') ?></li>
</ul>

<h3>Сменить тариф</h3>
<form id="tariff-form">
    <select name="new_tariff_id" class="form-control">
        <?php foreach ($tariffs as $tariff): ?>
            <option value="<?= $tariff->id ?>"><?= Html::encode($tariff->name) ?></option>
        <?php endforeach; ?>
    </select>
    <br>
    <button type="submit" class="btn btn-primary">Изменить тариф</button>
</form>

<div id="tariff-message"></div>

<br>
<button class="btn btn-secondary" id="load-history">История смены тарифа</button>
<div id="history-modal"></div>

<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historyModalLabel">История смены тарифов</h5>
            </div>
            <div class="modal-body" id="history-modal-body">
            </div>
        </div>
    </div>
</div>


<?php
$js = <<<JS
$('#load-history').on('click', function() {
    $.get('/subscriber-web/history?id={$model->id}', function(data) {
        $('#history-modal-body').html(data);
        $('#historyModal').modal('show');
    });
});
JS;
$this->registerJs($js);
?>


<?php
$url = \yii\helpers\Url::to(['/subscriber/update-tariff', 'id' => $model->id]);

$js = <<<JS
$('#tariff-form').on('submit', function(e) {
    e.preventDefault();
    const form = $(this);
    const data = form.serialize();

    $.post('$url', data)
        .done(function(response) {
            if (response.status === 'ok') {
                $('#tariff-message').html(
                    '<div class="alert alert-success">Тариф успешно обновлён</div>'
                );
            } else {
                let messages = '';

                if (response.errors) {
                    for (const field in response.errors) {
                        messages += response.errors[field].join('<br>') + '<br>';
                    }
                } else if (response.message) {
                    messages = response.message;
                } else {
                    messages = 'Неизвестная ошибка';
                }

                $('#tariff-message').html(
                    '<div class="alert alert-danger">' + messages + '</div>'
                );
            }
        })
        .fail(function(xhr) {
            const errMsg = xhr.responseJSON?.message || 'Ошибка при отправке запроса';
            $('#tariff-message').html(
                '<div class="alert alert-danger">Ошибка: ' + errMsg + '</div>'
            );
        });
});
JS;

$this->registerJs($js);
?>


