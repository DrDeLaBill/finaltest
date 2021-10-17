<?php

/* @var $this yii\web\View */

use app\assets\IndexAsset;

IndexAsset::register($this);

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <div class="dropdown" id="dropdown-cities">
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
        reportsUpdateProcess();

        <?php foreach ($cities as $city): ?>
            jQuery("#city-<?= $city->id ?>").on('click', function () {
                getReportsById(<?= $city->id ?>);
                setSessionCityById("<?= $city->id ?>");
            });
        <?php endforeach; ?>
    }
</script>
