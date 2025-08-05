<?php

namespace app\controllers;

use app\models\Tariff;
use app\models\TariffChangeHistory;
use Yii;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\Response;

class TariffChangeHistoryController extends Controller{
    public function behaviors(){
        $behaviors = array_merge(parent::behaviors(), [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => ['application/json'=>Response::FORMAT_JSON],
            ],
        ]);
    }

    public function actionHistory($subscriber_id){
        $history=TariffChangeHistory::find()
            ->where(['subscriber_id'=>$subscriber_id])
            ->orderBy(['changed_at'=>SORT_DESC])
            ->asArray()
            ->all();

        foreach($history as $history){
            $history['old_tariff_name']=Tariff::find()
                ->select('name')
                ->where(['id' => $history['old_tariff_id']])
                ->scalar();

            $history['new_tariff_name']=Tariff::find()
                ->select('name')
                ->where(['id' => $history['new_tariff_id']])
                ->scalar();
        }
        return $history;
    }
}