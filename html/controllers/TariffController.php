<?php

namespace app\controllers;

use yii\rest\Controller;
use yii\web\Response;
use yii\filters\ContentNegotiator;
use app\models\Tariff;

/**
 * @OA\Tag(
 *     name="Tariff",
 *     description="Список тарифов"
 * )
 */
class TariffController extends Controller
{
    public function behaviors(){
        return array_merge(parent::behaviors(),[
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => ['application/json' => Response::FORMAT_JSON],
            ],
        ]);
    }

    public function actionList(){
        return Tariff::find()
            ->orderBy(['id' => SORT_ASC])
            ->asArray()
            ->all();
    }
}