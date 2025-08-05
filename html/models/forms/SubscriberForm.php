<?php
namespace app\models\forms;

use yii\base\Model;
use app\models\Subscriber;

class SubscriberForm extends Model{
    public $full_name;
    public $city_code;
    public $contract;
    public $phone;
    public $address;
    public $tariff_id;

    public function rules(){
        return [
                [['full_name', 'city_code', 'contract', 'phone', 'address', 'tariff_id'], 'required'],
            ['city_code', 'match', 'pattern' => '/^\d{2}$/'],
            ['contract', 'match', 'pattern' => '/^\d{6}$/'],
            ['phone', 'match', 'pattern' => '/^\+375\d{9}$/'],
        ];
    }

    public function save(){
        if(!$this->validate())return false;
        $subscriber = new Subscriber();
        $subscriber->full_name=$this->full_name;
        $subscriber->contract_number=$this->city_code.$this->contract;
        $subscriber->phone=$this->phone;
        $subscriber->address=$this->address;
        $subscriber->tariff_id=$this->tariff_id;
        $subscriber->created_at=date('Y-m-d H:i:s');

        return $subscriber->save();
    }

    public function attributeLabels()
    {
        return [
            'full_name' => 'ФИО',
            'city_code' => 'Код города',
            'contract' => 'Номер договора (6 цифр)',
            'phone' => 'Телефон',
            'address' => 'Адрес',
            'tariff_id' => 'Тарифный план',
        ];
    }
}