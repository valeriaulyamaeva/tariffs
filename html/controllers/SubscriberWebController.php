<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Subscriber;
use app\models\Tariff;
use app\models\TariffChangeHistory;

class SubscriberWebController extends Controller
{
    public function actionCreate()
    {
        $model = new Subscriber();
        $tariffs = Tariff::find()->all();

        if ($model->load(Yii::$app->request->post())) {
            $cityCode = $model->city_code;
            $contractTail = $model->contract;

            if ($cityCode && preg_match('/^\d{2}$/', $cityCode)) {
                $contractTail = substr(str_pad(preg_replace('/\D/', '', $contractTail), 6, '0', STR_PAD_LEFT), 0, 6);
                $model->contract_number = $cityCode . $contractTail;
                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } else {
                $model->addError('city_code', 'Неверный код города');
            }
        }

        return $this->render('create', [
            'model' => $model,
            'tariffs' => $tariffs
        ]);
    }

    public function actionView($id)
    {
        $model = Subscriber::findOne($id);
        $tariffs = Tariff::find()->all();

        if (!$model) {
            throw new NotFoundHttpException('Абонент не найден');
        }

        return $this->render('view', [
            'model' => $model,
            'tariffs' => $tariffs
        ]);
    }

    public function actionSearch($q = null)
    {
        $query = Subscriber::find();

        if ($q) {
            $query->where(['like', 'full_name', $q])
                ->orWhere(['like', 'contract_number', $q])
                ->orWhere(['like', 'address', $q]);
        }

        $subscribers = $query->orderBy(['is_blocked' => SORT_ASC])->all();

        return $this->render('search', [
            'subscribers' => $subscribers,
            'q' => $q
        ]);
    }

    public function actionHistory($id)
    {
        $history = TariffChangeHistory::find()
            ->where(['subscriber_id' => $id])
            ->orderBy(['changed_at' => SORT_DESC])
            ->all();

        return $this->renderPartial('_history_modal', [
            'history' => $history
        ]);
    }
}
