<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class TariffChangeHistory extends ActiveRecord{

    public static function tableName(): string
    {
        return '{{%tariff_change_history}}';
    }

    public function rules(): array
    {
        return [
            [['subscriber_id', 'old_tariff_id', 'new_tariff_id'], 'required'],
            [['subscriber_id', 'old_tariff_id', 'new_tariff_id'], 'integer'],
        ];
    }

    public function getSubscriber()
    {
        return $this->hasOne(Subscriber::class, ['id' => 'subscriber_id']);
    }

    public function getOldTariff(){
        return $this->hasOne(Tariff::class, ['id' => 'old_tariff_id']);
    }

    public function getNewTariff(){
        return $this->hasOne(Tariff::class, ['id' => 'new_tariff_id']);
    }

    public static function getHistoryBySubscriber($subscriber_id): array
    {
        return self::find()
            ->where(['subscriber_id' => $subscriber_id])
            ->orderBy(['changed_at' => SORT_DESC])
            ->all();
    }
}