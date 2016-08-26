<?php
namespace common\behaviors;

use Yii;
use yii\imagine\Image;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;


class ImageUploadModel extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $uploadFiles;

    public function rules()
    {
        return [
            [['uploadFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, gif', 'maxFiles' => 10],
        ];
    }
}
?>
