<?php

namespace app\assets;

use yii\web\AssetBundle;

class NewReportAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/new-report.css',
    ];
    public $js = [
        'js/new-report.js',
        'https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}