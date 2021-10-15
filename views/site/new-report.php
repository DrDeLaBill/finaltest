<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\assets\NewReportAsset;

/* @var $this yii\web\View */
/* @var $model app\models\ReportForm */
/* @var $model yii\widgets\ActiveForm */

NewReportAsset::register($this);
?>

<div class="report-form">
    <div class="search_form">

        <?php $form = ActiveForm::begin(['id' => 'reportForm']); ?>

        <div class="search_box p-0">
            <?= $form->field($model, 'city', ['options' => ['class' => 'form-group mb-0']])->textInput([
                'id' => 'city',
                'placeholder' => 'Введите город (по умолчанию: все города)',
                'autocomplete' => 'off'
             ]) ?>
            <div class="search_box-result" id="search_box-result"></div>
        </div>

        <?= $form->field($model, 'title')->textInput([
            'maxlength' => true,
            'id' => 'title'
         ]) ?>

        <?= $form->field($model, 'text')->textarea([
            'rows' => 6,
            'id' => 'text'
        ]) ?>

        <?= $form->field($model, 'rating')->dropDownList(
            [
                '1' => 1,
                '2' => 2,
                '3' => 3,
                '4' => 4,
                '5' => 5
            ],
            ['id' => 'rating']
        ) ?>

        <?php ActiveForm::end(); ?>

        <?php $form = ActiveForm::begin([
            'id' => 'imageFile',
            'options' => ['enctype' => 'multipart/form-data']
        ]) ?>

        <?= $form->field($image, 'imageFile')->fileInput() ?>

        <?= $form->field($image, 'idReport')->textInput([
            'id' => 'idReport',
            'hidden' => true,
        ])->label(false) ?>

        <?php ActiveForm::end() ?>

        <div class="form-group">
            <?= Html::Button('Save', [
                'class' => 'btn btn-success',
                'id' => 'submit'
            ]) ?>
        </div>
    </div>
</div>