<?php
namespace common\models;

use Yii;
use yii\imagine\Image;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

function func1($f1, $f2)
{
    if ($f1['is_anons'] && !$f2['is_anons']) return 1;
    if (!$f1['is_anons'] && $f2['is_anons']) return -1;
    if ($f1['file'] > $f2['file']) return 1;
    if ($f1['file'] < $f2['file']) return -1;
    return 0;
}

class CenterPictures extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $uploadFiles;
    public $imageFiles;
    public $id;
    public $name;

    public function initData($center_id)
    {
        if (!$center_id)
            return false;
        $this->id = $center_id;
        $this->name = Yii::$app->db
            ->createCommand('SELECT name FROM center WHERE id=:id', [':id' => $center_id])
            ->queryScalar();

        $this->imageFiles = CenterPictures::readPictures($this->id);

        return true;
    }

    public function rules()
    {
        return [
            [['uploadFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxFiles' => 4],
        ];
    }

    public function upload()
    {
        if ($this->validate())
        {
            $path = Yii::getAlias('@webroot/upload/centers/'.$this->id);
            if(!is_dir($path))
            {
                FileHelper::createDirectory($path);
                if (!is_dir($path))
                    return false;
            }
            foreach ($this->uploadFiles as $file)
            {
                $file->saveAs($path . DIRECTORY_SEPARATOR . $file->baseName.'.'.$file->extension);
                Yii::$app->db->createCommand('INSERT INTO image (name, cid) VALUES (:name, :cid)', [':name' => $file->baseName.'.'.$file->extension, ':cid' => $this->id])
                    ->execute();

            }
            return true;
        }
        else
            return false;
    }

    private static function fileCmp($f1, $f2)
    {
        if ($f1['is_anons'] && !$f2['is_anons']) return 1;
        if (!$f1['is_anons'] && $f2['is_anons']) return -1;
        if ($f1['file'] > $f2['file']) return 1;
        if ($f1['file'] < $f2['file']) return -1;
        return 0;
    }

    public static function readPictures($center_id, $generate_thumbnails = true)
    {
        $imageFiles = array();
        if (!$center_id)
            return false;
        $upload_path = Yii::getAlias('@webroot/upload/centers/'.$center_id);
        $upload_url = 'upload/centers/'.$center_id;
        $tmp_path = Yii::getAlias('@webroot/tmp/centers/'.$center_id);
        $tmp_url = 'tmp/centers/'.$center_id;
        if (is_dir($upload_path))
        {
            if(!is_dir($tmp_path) && $generate_thumbnails)
                FileHelper::createDirectory($tmp_path);

            // $anons_image_name = Yii::$app->db->createCommand('SELECT name FROM image WHERE is_anons = 1 AND cid = :cid', [':cid' => $center_id])
            //     ->queryScalar();
            // $file_anons = FileHelper::findFiles($upload_path, ['only' => [$anons_image_name]]);
            // $file_others = FileHelper::findFiles($upload_path, ['except' => [$anons_image_name]]);
            // $files = array_merge($file_anons, $file_others);
            $files = FileHelper::findFiles($upload_path);
            foreach ($files as $file)
            {
                //$name = basename($file);
                $name = end(explode('/',$file));
                //echo '<script>alert("'.$name.'");</script>';
                $thumb = $tmp_path.'/'.$name;
                if (!file_exists($thumb) && $generate_thumbnails)
                  Image::thumbnail($file, 90, 90)
                    ->save($thumb, ['quality' => 50]);
                $is_anons = Yii::$app->db->createCommand('SELECT is_anons FROM image WHERE name = :name AND cid = :cid', [':name' => $name, ':cid' => $center_id])
                    ->queryScalar();
                $imageFiles[] = array(
                    'file' => $upload_url.'/'.$name,
                    'thumbnail' => $tmp_url.'/'.$name,
                    'is_anons' => $is_anons,
                );
                //Yii::info($file, 'myd');
            }

            //usort($imageFiles, 'fileCmp');
            usort($imageFiles, function ($f1, $f2)
                                {
                                    if ($f1['is_anons'] && !$f2['is_anons']) return -1;
                                    if (!$f1['is_anons'] && $f2['is_anons']) return 1;
                                    if ($f1['file'] > $f2['file']) return 1;
                                    if ($f1['file'] < $f2['file']) return -1;
                                    return 0;
                                }
            );
        }
        return $imageFiles;
    }

    public static function deleteFile($center_id, $filename)
    {
        $imageFiles = array();
        if (!$center_id || !$filename)
            return false;
        $upload_path = Yii::getAlias('@webroot/upload/centers/'.$center_id);
        $tmp_path = Yii::getAlias('@webroot/tmp/centers/'.$center_id);
        if (file_exists($upload_path.'/'.$filename))
        {
            unlink($upload_path.'/'.$filename);
            Yii::$app->db->createCommand('DELETE FROM image WHERE name = :name AND cid = :cid', [':name' => $filename, ':cid' => $center_id])
                ->execute();

            //Yii::info('Files count: '.(count(scandir($upload_path))),'myd');
            //Yii::info('Scandir: '.(json_encode(scandir($upload_path))),'myd');
            if (count(scandir($upload_path)) == 2) //Только . и ..
                rmdir($upload_path);
        }
        if (file_exists($tmp_path.'/'.$filename))
        {
            unlink($tmp_path.'/'.$filename);
            if (count(scandir($tmp_path)) == 2) //Только . и ..
                rmdir($tmp_path);
        }

        return true;
    }

    public static function fileSetAsAnons($center_id, $filename)
    {
        Yii::$app->db->createCommand('UPDATE image SET is_anons = 1 WHERE name = :name AND cid = :cid', [':name' => $filename, ':cid' => $center_id])
            ->execute();

        Yii::$app->db->createCommand('UPDATE image SET is_anons = 0 WHERE name != :name AND cid = :cid', [':name' => $filename, ':cid' => $center_id])
            ->execute();
        return true;
    }

}
?>
