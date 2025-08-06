<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\Subscriber;
use app\models\TariffChangeHistory;
use yii\web\NotFoundHttpException;

class TariffChangeForm extends Model{
    public $subscriber_id;
    public $new_tariff_id;

    public function rules(): array
    {
        return [
            [['subscriber_id', 'new_tariff_id'], 'required'],
        ];
    }

    public function change(): bool
    {
        if (!$this->validate()) return false;

        $subscriber = Subscriber::findOne($this->subscriber_id);
        if (!$subscriber) {
            throw new NotFoundHttpException('Абонент не найден');
        }

        if ($subscriber->tariff_id == $this->new_tariff_id) {
            $this->addError('new_tariff_id', 'Новый тариф должен отличаться от текущего.');
            return false;
        }

        $last_change = TariffChangeHistory::find()
            ->where(['subscriber_id' => $this->subscriber_id])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        if ($last_change && strtotime($last_change->changed_at) > strtotime('-1 day')) {
            $this->addError('new_tariff_id', 'Тариф можно менять раз в сутки.');
            return false;
        }

        $oldTariff = $subscriber->tariff_id;
        $subscriber->tariff_id = $this->new_tariff_id;

        if ($subscriber->save()) {
            $history = new TariffChangeHistory([
                'subscriber_id' => $subscriber->id,
                'old_tariff_id' => $oldTariff,
                'new_tariff_id' => $this->new_tariff_id,
                'changed_at' => date('Y-m-d H:i:s'),
            ]);
            return $history->save();
        }
        return false;
    }

}