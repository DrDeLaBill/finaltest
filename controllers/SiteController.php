<?php

namespace app\controllers;

use app\models\City;
use app\models\Report;
use app\models\ReportForm;
use app\models\UploadImageForm;
use app\models\User;
use phpDocumentor\Reflection\Types\This;
use UploadForm;
use Yii;
use yii\base\Model;
use yii\filters\AccessControl;
use yii\helpers\Console;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\UploadedFile;

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

    public function actionGetReports($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Report::find()->where(['id_city' => $id])->all();
    }

    public function actionGetCityByName($name)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return City::find()->where(['name' => $name])->one();
    }


    public function actionGetCityNameById($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return City::find()->where(['id' => $id])->one()->name;
    }

    public function actionGetCityIdByName($name)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return City::find()->where(['name' => $name])->one()->id;
    }

    public function actionReport($id)
    {
        return $this->render('report', [
            'report' => Report::findOne(['id' => $id])
        ]);
    }

    public function actionNewReport()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('index');
        }
        return $this->render('new-report', [
            'model' => new ReportForm(),
            'image' => new UploadImageForm()
        ]);
    }

    public function actionSaveReport()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $form = Yii::$app->request->post('form');

        $report = new Report();
        $report->id_city = $this->actionGetCityIdByName($form[1]['value']);
        $report->title = $form[2]['value'];
        $report->text = $form[3]['value'];
        $report->id_author = Yii::$app->user->identity->getId();
        $report->rating = $form[4]['value'];

        if ($report->validate() and $report->save()) {
            return $report->id;
        }
        return false;
    }

    public function actionSaveReportImage()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $image = new UploadImageForm();
        if ($image->load(Yii::$app->request->post())) {
            $image->imageFile = UploadedFile::getInstance($image, 'imageFile');
            $imageName = $image->uploadImage($image->imageFile);
            $report = Report::findOne(['id' => $image->idReport]);
            $report->setImage($imageName);
            return $imageName;
        }
        return false;
    }

    public function actionSetSessionCityById($city_id)
    {
        $session = Yii::$app->session;
        $session->open();
        $session->set('city_id', $city_id);
    }

    public function actionSetSessionCityByName($city_name)
    {
        $session = Yii::$app->session;
        $session->open();
        $id = City::findOne(['name' => $city_name])->id;
        $session->set('city_id', $id);
    }

    public function actionGetSessionCity()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $session = Yii::$app->session;
        $session->open();
        if ($session->has('city_id')) {
            return $session['city_id'];
        }
        return false;
    }

    public function actionGetReportAuthor($id_author)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return User::findOne(['id' => $id_author]);
    }

    public function actionGetCitiesLike($search)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return City::find()->where(['like', 'name', '%' . $search . '%', false])->all();
    }

}
