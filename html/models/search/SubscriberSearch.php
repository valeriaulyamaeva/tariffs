<?php
namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Subscriber;

class SubscriberSearch extends Subscriber
{
    public function rules(): array
    {
        return [
            [['full_name', 'contract_number', 'phone', 'address'], 'safe'],
            ['is_blocked', 'boolean'],
        ];
    }

    public function search($params){
        $query = Subscriber::find()->joinWith('tariff');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['is_blocked' => SORT_ASC]],
        ]);

        $this->load($params);
        if (!$this->validate()) return $dataProvider;
        $query->andFilterWhere(['is_blocked' => $this->is_blocked]);
        return $dataProvider;
    }
}