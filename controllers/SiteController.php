<?php

namespace app\controllers;

use app\assets\EditReportAsset;
use app\models\City;
use app\models\Report;
use app\models\ReportForm;
use app\models\UploadImageForm;
use app\models\User;
use phpDocumentor\Reflection\Types\This;
use Yii;
use yii\base\Model;
use yii\filters\AccessControl;
use yii\helpers\Console;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
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
            'cities' => City::find()->orderBy(['name' => SORT_ASC])->all(),
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

    public function actionEditReport($id) {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('index');
        }
        $report = Report::findOne(['id' => $id]);
        $reportForm = new ReportForm();
        $reportForm->attributes = $report->attributes;
        $reportForm->city = $report->city->name;

        return $this->render('edit-report', [
            'model' => $reportForm,
            'image' => new UploadImageForm(),
            'imageName' => $report->img,
            'id' => $id
        ]);
    }

    public function actionAuthor($id) {
        return $this->render('author', [
            'reports' => Report::find()->where(['id_author' => $id])->all()
        ]);
    }

    public function actionSaveEditReport() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $reportForm = new ReportForm();
        if ($reportForm->load(Yii::$app->request->post())) {
            $report = Report::findOne(['id' => $reportForm->id]);
            $report->attributes = $reportForm->attributes;
            $report->id_city = $this->actionGetCityIdByName($reportForm['city']);
            if ($report->validate() and $report->save(false)) {
                return $report->id;
            }
        }
        return false;
    }

    public function actionGetImageName($id) {
        return Report::findOne(['id' => $id])->img;
    }

    public function actionIsGuest() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Yii::$app->user->isGuest;
    }

    public function actionGetReports($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $form = Yii::$app->request->post('form');
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

    public function actionSaveReport()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $reportForm = new ReportForm();
        if ($reportForm->load(Yii::$app->request->post())) {
            $report = new Report();
            $report->attributes = $reportForm->attributes;
            $this->addNewCity($reportForm['city']);
            $report->id_city = $this->actionGetCityIdByName($reportForm['city']);
            $report->id_author = Yii::$app->user->identity->getId();
            if ($report->validate() and $report->save()) {
                return $report->id;
            }
        }
        return false;
    }

    public function actionSaveReportImage()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $image = new UploadImageForm();
        if ($image->load(Yii::$app->request->post())) {
            $image->imageFile = UploadedFile::getInstance($image, 'imageFile');
            if ($image->validate()) {
                $report = Report::findOne(['id' => $image->idReport]);
                $imageName = $image->uploadImage($image->imageFile, $report->img);
                $report->setImage($imageName);
                return $imageName;
            }
        }
        return false;
    }

    public function actionDeleteReport($id) {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        $report = Report::findOne(['id' => $id]);
        $image = new UploadImageForm();
        $image->deleteFile($report->img);
        return $report->delete();
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

    public function addNewCity($cityName): bool {
        if ($cityName != "" and !City::find()->where(['name' => $cityName])->exists()) {
            $city = new City();
            $city->name = $cityName;
            return $city->save();
        }
        return false;
    }
}
