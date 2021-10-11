<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ReportForm */
/* @var $model yii\widgets\ActiveForm */
?>

<div class="report-form">
    <div class="search_form">

        <?php $form = ActiveForm::begin(); ?>

        <div class="search_box">
            <?= $form->field($model, 'city')->textInput([
                 'id' => 'search',
                 'placeholder' => 'Введите город'
             ]) ?>
            <div class="search_box-result" id="search_box-result"></div>
        </div>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

        <div class="form-group">
            <?= Html::submitButton('Save', [
                'class' => 'btn btn-success',
                'id' => 'submit'
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
