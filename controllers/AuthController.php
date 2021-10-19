<?php


namespace app\controllers;


use app\models\LoginForm;
use app\models\SignupForm;
use app\services\SignupService;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class AuthController extends Controller
{
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
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            try{
                if($model->login()){
                    return $this->goBack();
                }
            } catch (\DomainException $e){
                Yii::$app->session->setFlash('error', $e->getMessage());;
            }
        }

        $model->password = '';
        return $this->render(
            'login',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goBack();
    }

    public function actionSignup()
    {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $signupService = new SignupService();
            try{
                $user = $signupService->signup($model);
                Yii::$app->session->setFlash('success', 'Check your email to confirm the registration.');
                $signupService->sentEmailConfirm($user);
                return $this->goHome();
            } catch (\RuntimeException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render(
            'signup',
            [
                'model' => $model
            ]
        );
    }

    public function actionSignupConfirm($token)
    {
        $signupService = new SignupService();

        try{
            $signupService->confirmation($token);
            Yii::$app->session->setFlash('success', 'You have successfully confirmed your registration.');
        } catch (\Exception $e){
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->goHome();
    }
}