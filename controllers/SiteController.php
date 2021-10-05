<?php

namespace app\controllers;

use app\models\City;
use app\models\Report;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'cities' => City::find()->all(),
        ]);
    }

    public function actionGetReports($id) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Report::find()->where(['id_city' => $id])->all();
    }

    public function actionGetCityByName($name) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return City::find()->where(['name' => $name])->one();
    }

    public function actionGetCityNameById($id) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return City::find()->where(['id' => $id])->one()->name;
    }

    public function actionReport($id) {
        return $this->render('report', [
            'report' => Report::findOne(['id' => $id])
        ]);
    }
}
