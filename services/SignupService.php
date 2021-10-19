<?php

namespace app\services;

use app\models\SignupForm;
use app\models\User;
use Yii;

class SignupService
{

    public function signup(SignupForm $form)
    {
        $user = new User();
        $user->attributes = $form->attributes;
        $user->setPassword($form->password);
        $user->email_confirm_token = Yii::$app->security->generateRandomString();
        $user->status = User::STATUS_WAIT;

        if(!$user->save()){
            throw new \RuntimeException('Saving error.');
        }

        return $user;
    }


    public function sentEmailConfirm(User $user)
    {
        $email = $user->email;

        $sent = Yii::$app->mailer
            ->compose(
                ['html' => 'user-signup-comfirm-html', 'text' => 'user-signup-comfirm-text'],
                ['user' => $user])
            ->setTo($email)
            ->setFrom('finaltest@admin.com')
            ->setSubject('Confirmation of registration')
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Sending error.');
        }
    }


    public function confirmation($token): void
    {
        if (empty($token)) {
            throw new \DomainException('Empty confirm token.');
        }

        $user = User::findOne(['email_confirm_token' => $token]);
        if (!$user) {
            throw new \DomainException('User is not found.');
        }

        $user->email_confirm_token = null;
        $user->status = User::STATUS_ACTIVE;
        if (!$user->save()) {
            throw new \RuntimeException('Saving error.');
        }

        if (!Yii::$app->getUser()->login($user)){
            throw new \RuntimeException('Error authentication.');
        }
    }

}