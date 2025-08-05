<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use app\models\forms\SubscriberForm;
use app\models\forms\TariffChangeForm;
use app\models\Subscriber;

class SubscriberController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['POST'],
                    'update-tariff' => ['POST'],
                    'view' => ['GET'],
                    'search' => ['GET'],
                ],
            ],
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => ['application/json' => Response::FORMAT_JSON],
            ],
        ]);
    }

    public function actionCreate()
    {
        $form = new SubscriberForm();
        $data = Yii::$app->request->post();
        $form->load($data, '');

        if ($form->save()) {
            return ['status' => 'ok', 'message' => 'Абонент создан'];
        }

        throw new BadRequestHttpException(json_encode($form->getErrors()));
    }

    public function actionUpdateTariff($id)
    {
        $form = new TariffChangeForm();
        $form->subscriber_id = $id;
        $form->new_tariff_id = Yii::$app->request->post('new_tariff_id');

        if ($form->change()) {
            return ['status' => 'ok', 'message' => 'Тариф обновлён'];
        }

        if (empty($form->getErrors()) && Subscriber::findOne($id) === null) {
            throw new NotFoundHttpException('Абонент не найден');
        }

        return ['status' => 'error', 'errors' => $form->getErrors()];
    }

    public function actionView($id)
    {
        $subscriber = Subscriber::find()
            ->with('tariff')
            ->where(['id' => $id])
            ->asArray()
            ->one();

        if (!$subscriber) {
            throw new NotFoundHttpException('Абонент не найден');
        }

        return [
            'status' => 'ok',
            'message' => 'Абонент найден',
            'data' => $subscriber
        ];
    }

    public function actionSearch($query = '')
    {
        $subscribers = Subscriber::find()
            ->where(['like', 'full_name', $query])
            ->orWhere(['like', 'contract_number', $query])
            ->orWhere(['like', 'address', $query])
            ->orderBy(['is_blocked' => SORT_ASC])
            ->asArray()
            ->all();

        return [
            'status' => 'ok',
            'message' => 'Результаты поиска',
            'data' => $subscribers
        ];
    }
}
