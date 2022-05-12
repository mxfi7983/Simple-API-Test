<?php

namespace frontend\controllers;

use yii\web\Response;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\filters\ContentNegotiator;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET'],
            'view' => ['GET'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ]
        ]);
    }

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        // todo: best way to create separate Actions
        unset($actions['index'], $actions['view']);

        return $actions;
    }

    /**
     * @return ActiveDataProvider
     */
    public function actionIndex()
    {
        $this->checkAccess($this->action->id);

        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->where(['status' => User::STATUS_ACTIVE])
        ]);

        return $dataProvider;
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionView($id)
    {
        $model = $this->findUser($id);

        $this->checkAccess($this->action->id, $model);

        return $model;
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function findUser($id)
    {
        $class = $this->modelClass;
        $model = $class::find()->andWhere([
            'id' => $id,
            'status' => User::STATUS_ACTIVE,
        ])->with('albums')->one();

        if ($model === null) {
            throw new NotFoundHttpException("User not found: $id");
        }

        return $model;
    }

    /**
     * @param string $action
     * @param null $model
     * @param array $params
     * @return bool|void
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        return true;
    }
}