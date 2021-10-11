<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Report */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="report-form">
    <div class="search_form">
        <p>City</p>
        <div class="search_box">
            <form>
                <input type="text" name="search" id="search" placeholder="Введите город">
            </form>
            <div class="search_box-result" id="search_box-result">

            </div>
        </div>

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'img')->fileInput(['class' => 'form-file']) ?>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>

<script>
    window.onload = function () {
        let $result = $('#search_box-result');

        $('#search').on('keyup', function(){
            let search = $(this).val();
            if ((search != '') && (search.length > 1)){
                $.ajax({
                    type: "GET",
                    url: "/site/get-cities-like",
                    data: {
                        "search": search,
                    },
                    success: function(cities){
                        $result.html("");
                        for (i in cities) {
                            $result.append(
                                "<p class=\"search_result\" id=\"search_result\">" +
                                    cities[i].name +
                                "</p>"
                            );
                        }
                        if(cities.length != 0){
                            $result.fadeIn();
                        } else {
                            $result.fadeOut(100);
                        }
                    }
                });
            } else {
                $result.html('');
                $result.fadeOut(100);
            }
        });

        $("#search_box-result").on('click', '.search_result', function() {
            jQuery("#search").val($(this).text());
            $('#search_box-result').fadeOut(100);
        });

        $(document).on('click', function(e){
            if (!$(e.target).closest('.search_box').length){
                $result.html('');
                $result.fadeOut(100);
            }
        });

    };



</script>