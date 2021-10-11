<?php

namespace app\models;

use Yii;
use yii\base\Model;

class ReportForm extends Model
{
    public $city;
    public $title;
    public $text;

    public function rules()
    {
        return [
            [['city', 'text', 'title'], 'required'],
            [['city', 'title'], 'string', 'max' => 255],
            [['text'], 'string'],
        ];
    }
}