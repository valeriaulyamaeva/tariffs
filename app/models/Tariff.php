<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Tariff extends ActiveRecord{

    public static function tableName(){
        return 'tariff';
    }

    public function rules(){
        return [
            [['name'], 'required'],
            [['description'], 'string', 'max' => 255],
        ];
    }

    public function getSubscribers()
    {
        return $this->hasMany(Subscriber::class, ['tariff_id' => 'id']);
    }

    public function getDropdownList(){
        return self::find()
            ->select(['name', 'id'])
            ->indexBy('id')
            ->column();
    }
}