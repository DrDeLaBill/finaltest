<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <div class="dropdown">
        <h1>Выберите город:</h1>
        <button class="btn btn-secondary dropdown-toggle col-sm-6" type="button" id="dropdownMenuButton"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span id="user-city">Ваш город</span>
        </button>
        <div class="dropdown-menu col-sm-6" aria-labelledby="dropdownMenuButton">
            <?php foreach ($cities as $city): ?>
                <button class="dropdown-item" id="city-<?= $city->id ?>"><?= $city->name ?></button>
            <?php endforeach; ?>
        </div>
    </div>
    <div id="reports">

    </div>
</div>

<script>
    window.onload = function () {
        jQuery("#user-city").text(ymaps.geolocation.city);
        showReportByName();

        <?php foreach ($cities as $city): ?>
        jQuery("#city-<?= $city->id ?>").on('click', function () {
            getReports(<?= $city->id ?>);
        });
        <?php endforeach; ?>
    }
</script>
