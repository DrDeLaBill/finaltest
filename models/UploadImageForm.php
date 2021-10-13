<?php


namespace app\models;


use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadImageForm extends Model
{
    public $imageFile;
    public $idReport;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxFiles' => 1],
            [['idReport'], 'string']
        ];
    }

    public function uploadImage(UploadedFile $file, $currentImage = null)
    {
        if ($this->validate()) {
            $this->deleteFile($currentImage);
            return $this->saveFile($file);
        }
        return $currentImage;
    }

    private function getFolder()
    {
        return Yii::getAlias('@web') . 'uploads/';
    }

    private function generateFilename($file)
    {
        return md5(uniqid($file->baseName)) . '.' . $file->extension;
    }

    public function deleteFile($currentImage)
    {
        if (!empty($currentImage) && file_exists($this->getFolder() . $currentImage)) {
            unlink($this->getFolder() . $currentImage);
        }
    }

    private function saveFile($file)
    {
        $newFilename = $this->generateFilename($file);
        $file->saveAs($this->getFolder() . $newFilename);
        return $newFilename;
    }
}