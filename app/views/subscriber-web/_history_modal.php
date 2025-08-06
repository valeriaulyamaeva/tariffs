<?php
/** @var yii\web\View $this */
/** @var app\models\TariffChangeHistory[] $history */

use app\models\Tariff;

?>

<table class="table table-bordered">
    <thead>
    <tr>
        <th>Старый тариф</th>
        <th>Новый тариф</th>
        <th>Дата изменения</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($history as $entry): ?>
        <tr>
            <td><?= \app\models\Tariff::findOne($entry->old_tariff_id)->name ?? '-' ?></td>
            <td><?= \app\models\Tariff::findOne($entry->new_tariff_id)->name ?? '-' ?></td>
            <td><?= Yii::$app->formatter->asDate($entry->changed_at, 'php:d.m.Y') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

