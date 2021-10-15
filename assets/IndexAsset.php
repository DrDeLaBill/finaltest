<?php

namespace app\assets;

use yii\web\AssetBundle;

class IndexAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
        '//api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru-RU',
        'js/index.js',
        'https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}