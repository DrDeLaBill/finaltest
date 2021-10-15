<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class ReportForm extends Model
{
    public $id;
    public $city;
    public $title;
    public $text;
    public $rating;
    public $img;

    public function rules()
    {
        return [
            [['text', 'title'], 'required'],
            [['city', 'title'], 'string', 'max' => 255],
            [['text'], 'string'],
            [['rating'], 'integer'],
        ];
    }
}