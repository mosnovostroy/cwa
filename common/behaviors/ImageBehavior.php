<?php

namespace common\behaviors;


use Yii;
use yii\base\Behavior;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use yii\imagine\Image;
use common\behaviors\ImageUploadFilesModel;

/**
 * Class ImageBehavior
 */
class ImageBehavior extends Behavior
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Private members (will be available by getters or by corresponding "names" (see yii\base\Object class):
	private $_imageUploadModel = null;
	private $_images = [];

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Getters:
	public function getImageUploadModel ()
	{
		if (!$this->_imageUploadModel)
			$this->_imageUploadModel = new ImageUploadModel;
		return $this->_imageUploadModel;
	}

	public function getEntity()
	{
		return strtolower(end(explode('\\', $this->owner->className())));
	}

	public function getEntityId()
	{
		return $this->owner->id;
	}

    public function getLogoImage()
    {
		$entity = $this->entity;
		$entityId = $this->entityId;
		if (!$entity || !$entityId)
            return '';

		$filename = Yii::$app->db->createCommand('SELECT name FROM image WHERE entity = :entity AND cid = :cid AND is_logo = 1', [':entity' => $entity, ':cid' => $entityId])
			->queryScalar();

		if ($filename && 1)
		{
			return '/upload/'.$entity.'/'.$entityId.'/'.$filename;
		}

		return '';
    }

		public function getAnonsImage()
    {
		$entity = $this->entity;
		$entityId = $this->entityId;
		if (!$entity || !$entityId)
            return '';

		$filename = Yii::$app->db->createCommand('SELECT name FROM image WHERE entity = :entity AND cid = :cid AND is_anons = 1', [':entity' => $entity, ':cid' => $entityId])
			->queryScalar();

		if ($filename && 1)
		{
			return '/upload/'.$entity.'/'.$entityId.'/'.$filename;
		}

		return '';
    }

		////////////////////////////////////////////////////////////////////////////////////////////////
		// Generate preview with 4:3 ratio for main page, return the relative url:
		public function getAnons4x3()
    {
				return $this->doGetAnons(400, 300, '4x3');
    }

		////////////////////////////////////////////////////////////////////////////////////////////////
		// Generate preview with 3:2 ratio for centers list, return the relative url:
		public function getAnons3x2()
    {
				return $this->doGetAnons(450, 300, '3x2');
    }

		////////////////////////////////////////////////////////////////////////////////////////////////
		// Generate preview with specified sizes, saves to file with $prefix, return the relative url:
		public function doGetAnons($width, $height, $prefix)
    {
				$entity = $this->entity;
				$entityId = $this->entityId;
				if (!$entity || !$entityId)
            return '';

				$upload_path = Yii::getAlias('@webroot/upload/'.$entity.'/'.$entityId);
				$tmp_path = Yii::getAlias('@webroot/tmp/'.$entity.'/'.$entityId);
				$tmp_url = '/tmp/'.$entity.'/'.$entityId;

				$filename = Yii::$app->db->createCommand('SELECT name FROM image WHERE entity = :entity AND cid = :cid AND is_anons = 1', [':entity' => $entity, ':cid' => $entityId])
						->queryScalar();

				if ($filename && 1)
				{
						$original = $upload_path.'/'.$filename;
						$anons4x3 = $tmp_path.'/'.$prefix.'_'.$filename;
						if (file_exists($original) && !file_exists($anons4x3))
								Image::thumbnail($original, $width, $height)
										->save($anons4x3, ['quality' => 50]);
						return $tmp_url.'/'.$prefix.'_'.$filename;
				}

				return '';
    }

		public function doGetImages ($include_logo = true)
		{
			$entity = $this->entity;
			$entityId = $this->entityId;
			$generate_thumbnails = 1;
			if (!$entity || !$entityId)
	            return false;

	        $upload_path = Yii::getAlias('@webroot/upload/'.$entity.'/'.$entityId);
	        $upload_url = '/upload/'.$entity.'/'.$entityId;
	        $tmp_path = Yii::getAlias('@webroot/tmp/'.$entity.'/'.$entityId);
	        $tmp_url = '/tmp/'.$entity.'/'.$entityId;

			if (is_dir($upload_path))
	        {
	            if(!is_dir($tmp_path) && $generate_thumbnails)
	                FileHelper::createDirectory($tmp_path);

	            $files = FileHelper::findFiles($upload_path);
	            foreach ($files as $file)
	            {
	                $name = end(explode('/',$file));
	                $thumb = $tmp_path.'/'.$name;
	                if (!file_exists($thumb) && $generate_thumbnails)
	                  Image::thumbnail($file, 90, 90)
	                    ->save($thumb, ['quality' => 50]);
	                $is_anons = Yii::$app->db->createCommand('SELECT is_anons FROM image WHERE entity = :entity AND name = :name AND cid = :cid', [':name' => $name, ':entity' => $entity, ':cid' => $entityId])
	                    ->queryScalar();
									$is_logo = Yii::$app->db->createCommand('SELECT is_logo FROM image WHERE entity = :entity AND name = :name AND cid = :cid', [':name' => $name, ':entity' => $entity, ':cid' => $entityId])
			                    ->queryScalar();

									if (!$is_logo || $include_logo)
			                $this->_images[] = array(
			                    'file' => $upload_url.'/'.$name,
			                    'thumbnail' => $tmp_url.'/'.$name,
			                    'is_anons' => $is_anons,
													'is_logo' => $is_logo,
			                );
			                //Yii::info($file, 'myd');
	            }

	            //usort($imageFiles, 'fileCmp');
	            usort($this->_images, function ($f1, $f2)
	                                {
	                                    if ($f1['is_anons'] && !$f2['is_anons']) return -1;
	                                    if (!$f1['is_anons'] && $f2['is_anons']) return 1;
	                                    if ($f1['file'] > $f2['file']) return 1;
	                                    if ($f1['file'] < $f2['file']) return -1;
	                                    return 0;
	                                }
	            );
	        }
			return $this->_images;
		}

	public function getImages()
	{
			return $this->doGetImages(false);
	}

	public function getAllImages()
	{
			return $this->doGetImages(true);
	}

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Uploads files from POST and place them into the model:
    public function upload()
    {
		$this->ImageUploadModel->uploadFiles = UploadedFile::getInstances($this->ImageUploadModel, 'uploadFiles');
        if ($this->ImageUploadModel->validate())
        {
            $path = Yii::getAlias('@webroot/upload/'.$this->entity.'/'.$this->entityId);
            if(!is_dir($path))
            {
                FileHelper::createDirectory($path);
                if (!is_dir($path))
                    return false;
            }
            foreach ($this->ImageUploadModel->uploadFiles as $file)
            {
                $file->saveAs($path . DIRECTORY_SEPARATOR . $file->baseName.'.'.$file->extension);
                Yii::$app->db->createCommand('INSERT INTO image (entity, name, cid) VALUES (:entity, :name, :cid)', [':entity' => $this->entity, ':name' => $file->baseName.'.'.$file->extension, ':cid' => $this->entityId])
                    ->execute();

            }
            return true;
        }
        else
            return false;
    }

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Deletes specified file from model:
    public function deleteImage($filename)
    {
		$entity = $this->entity;
		$entityId = $this->entityId;
		if (!$entity || !$entityId)
            return false;

        $upload_path = Yii::getAlias('@webroot/upload/'.$entity.'/'.$entityId);
        $tmp_path = Yii::getAlias('@webroot/tmp/'.$entity.'/'.$entityId);
        if (file_exists($upload_path.'/'.$filename))
        {
            unlink($upload_path.'/'.$filename);
            Yii::$app->db->createCommand('DELETE FROM image WHERE entity = :entity AND name = :name AND cid = :cid', [':name' => $filename, ':entity' => $entity, ':cid' => $entityId])
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

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Choose specified file to be "main":
    public function setAnonsImage($filename)
    {
		$entity = $this->entity;
		$entityId = $this->entityId;
		if (!$entity || !$entityId)
            return false;

		Yii::$app->db->createCommand('UPDATE image SET is_anons = 1 WHERE entity = :entity AND name = :name AND cid = :cid', [':name' => $filename, ':entity' => $entity, ':cid' => $entityId])
            ->execute();

        Yii::$app->db->createCommand('UPDATE image SET is_anons = 0 WHERE entity = :entity AND name != :name AND cid = :cid', [':name' => $filename, ':entity' => $entity, ':cid' => $entityId])
            ->execute();

		return true;
    }

		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Choose specified file to be logo:
	    public function setLogoImage($filename)
	    {
			$entity = $this->entity;
			$entityId = $this->entityId;
			if (!$entity || !$entityId)
	            return false;

			Yii::$app->db->createCommand('UPDATE image SET is_logo = 1 WHERE entity = :entity AND name = :name AND cid = :cid', [':name' => $filename, ':entity' => $entity, ':cid' => $entityId])
	            ->execute();

	        Yii::$app->db->createCommand('UPDATE image SET is_logo = 0 WHERE entity = :entity AND name != :name AND cid = :cid', [':name' => $filename, ':entity' => $entity, ':cid' => $entityId])
	            ->execute();

			return true;
	    }
}
