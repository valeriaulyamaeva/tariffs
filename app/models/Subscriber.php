<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Subscriber extends ActiveRecord
{
    public $city_code;
    public $contract;

    public static function tableName(): string
    {
        return 'subscriber';
    }

    public function rules(): array
    {
        return [
            [['full_name', 'contract_number', 'phone', 'address', 'tariff_id'], 'required'],
            [ 'phone', 'match', 'pattern' => '/^\+375\d{9}$/'],
            ['is_blocked', 'boolean'],
            ['contract_number', 'match', 'pattern' => '/^\d{8}$/', 'message' => 'Номер договора должен содержать 8 цифр'],
            ['city_code', 'match', 'pattern' => '/^\d{2}$/', 'message' => 'Код города должен состоять из 2 цифр'],
            ['contract', 'match', 'pattern' => '/^\d{6}$/', 'message' => 'Часть номера договора должна состоять из 6 цифр'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'full_name' => 'ФИО',
            'contract_number' => 'Номер договора',
            'phone' => 'Телефон',
            'address' => 'Адрес',
            'tariff_id' => 'Тарифный план',
            'is_blocked' => 'Заблокирован',
            'city_code' => 'Код города',
        ];
    }

    public function getTariff()
    {
        return $this->hasOne(Tariff::class, ['id' => 'tariff_id']);
    }

    public function getTariffChanges()
    {
        return $this->hasMany(TariffChangeHistory::class, ['subscriber_id' => 'id'])
            ->orderBy(['changed_at' => SORT_DESC]);
    }
}
