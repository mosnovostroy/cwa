<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\behaviors\RegionInfoBehavior;
use yii\base\InvalidParamException;
use yii\helpers\FileHelper;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 20;

    const ROLE_USER = 10;
    const ROLE_ADMIN = 20;

    const AVATAR_NAME = 'av.jpg';


     /**
     * @var array EAuth attributes
     */
     public $profile;
	   public $authKey;

     // У разных сервисов подходящее нам фото называется по-разному. Здесь код для выбора нужного фото.
     public static function getPicture($service)
     {
       //return $service->getAttribute('photo_medium');

        // ВКонтакте
        if ($service->getAttribute('photo_medium'))
            return $service->getAttribute('photo_medium');

        // Mail.ru
        if ($service->getAttribute('pic_190'))
            return $service->getAttribute('pic_190');

        // Одноклассники
        if ($service->getAttribute('pic_3'))
            return $service->getAttribute('pic_3');

        return null;
     }

    /**
     * @param \nodge\eauth\ServiceBase $service
     * @return User
     * @throws ErrorException
     */
    public static function findByEAuth($service) {
        if (!$service->getIsAuthenticated()) {
            throw new ErrorException('EAuth user should be authenticated before creating identity.');
        }

		//Идентификатор пользователя типа mailru-10964094557919501470 - мы будем фиксировать его в поле таблицы user.email (моя идея-костыль)
        $id = $service->getServiceName().'-'.$service->getId();

		//Если такого юзера из соцсетей еще не было, запишем его в таблицу (мой код).
		$user = User::find()->where(['social_id' => $id])->one();
		if (!$user)
		{
			$values = [
				'username' => $service->getAttribute('name'),
				'role' => self::ROLE_USER,
				'social_id' => $id,
        'social_service_name' => $service->getServiceName(),
        'social_email' => $service->getAttribute('email'),
        'social_avatar' => $service->getAttribute('pic_190'),
			];

      Yii::info('Доступны такие атрибуты:', 'myd');
      Yii::info($service->attributes, 'myd');

      Yii::info('Значения таковы:', 'myd');
      Yii::info($values, 'myd');

			$user = new User();
			$user->attributes = $values;

      Yii::info('Передали в атрибуты вот что:', 'myd');
      Yii::info($user->attributes, 'myd');

			if($user->save())
			{
          //$user->updateAvatar();

          // $auth = Yii::$app->authManager;
				  // $authorRole = $auth->getRole('author');
				  // $auth->assign($authorRole, $user->getId());
			}
		}
    else if ($user->social_id)
    {
        // Если в соцсети у пользователя поменялась картинка (следует ли это из смены урла?):
        $current_service_picture = $this->getPicture($service);
        if ($this->social_avatar != $current_service_picture)
        {
            $this->social_avatar = $current_service_picture;
            $user->save();
            $user->updateAvatar();
        }
    }


		//Пишем специфические для авторизации через сети вещи в сессию (код автора расширения):
        $attributes = [
            'id' => $id,
            'username' => $service->getAttribute('name'),
            'authKey' => md5($id),
            'profile' => $service->getAttributes(),
        ];
        $attributes['profile']['service'] = $service->getServiceName();
        Yii::$app->getSession()->set('user-'.$id, $attributes);

		//Yii::info($user->getAttributes(), 'myd');
        return $user;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        if (Yii::$app->getSession()->has('user-'.$id)) {
            return new self(Yii::$app->getSession()->get('user-'.$id));
        }
        else {
            return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'regionInfo' => [
                'class' => RegionInfoBehavior::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_DELETED, self::STATUS_ACTIVE, self::STATUS_INACTIVE ]],
            ['role', 'in', 'range' => [self::ROLE_USER, self::ROLE_ADMIN]],
			      [['username', 'email', 'social_id', 'social_service_name', 'social_email', 'social_avatar', 'region'], 'safe'],
        ];
    }


    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public static function findBySignupConfirmToken($token)
    {
        if (!static::isSignupConfirmTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_INACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public static function isSignupConfirmTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.signupConfirmTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public static function isUser()
    {
        if (Yii::$app->user && Yii::$app->user->identity)
        {
            return true;
        } else {
            return false;
        }
    }

    public static function isAdmin()
    {
        if (Yii::$app->user && Yii::$app->user->identity && static::findOne(['username' => Yii::$app->user->identity->username, 'role' => self::ROLE_ADMIN]))
        {
            return true;
        } else {
            return false;
        }
    }

    public static function isOwner($id)
    {
        if (Yii::$app->user && Yii::$app->user->identity && Yii::$app->user->identity->id === $id)
        {
            return true;
        } else {
            return false;
        }
    }

    public static function isAdminOrOwner($id)
    {
        if (User::isAdmin() || User::isOwner($id))
        {
            return true;
        } else {
            return false;
        }
    }

    // public static function isUserAdmin($username)
    // {
        // if (static::findOne(['username' => $username, 'role' => self::ROLE_ADMIN]))
        // {
            // return true;
        // } else {
            // return false;
        // }
    // }

    public function attributeLabels()
    {
        return [
            'username' => 'Имя',
            'email' => 'Email',
            'created_at' => 'Дата регистрации',
            'region' => 'Ваш регион',
        ];
    }

    public function updateAvatar()
    {
        $entity = 'user';
			  $entityId = $this->id;
			  if (!$entity || !$entityId)
	         return false;

	      $upload_path = Yii::getAlias('@webroot/upload/'.$entity.'/'.$entityId);
	      $tmp_path = Yii::getAlias('@webroot/tmp/'.$entity.'/'.$entityId);

        // Если нужно, создаем директорию для картинок юзера:
        if(!is_dir($upload_path))
        {
            FileHelper::createDirectory($upload_path);
            if (!is_dir($upload_path))
                return false;
        }

        // Если нужно, создаем директорию для превью картинок юзера:
        if(!is_dir($tmp_path))
        {
            FileHelper::createDirectory($tmp_path);
            if (!is_dir($tmp_path))
                return false;
        }

        // Скачиваем и сохраняем аватарку; генерируем превьюшку:
        $file = null; //получить сюда файл по урлу $this->social_avatar
        if ($file)
        {
            $file->saveAs($upload_path . DIRECTORY_SEPARATOR . self::AVATAR_NAME);

            // Генерируем 50х50 превью аватарки:
            Image::thumbnail($file, 50, 50)
                ->save($tmp_path . DIRECTORY_SEPARATOR . self::AVATAR_NAME, ['quality' => 50]);
        }
    }

    public function getAvatar()
    {
        $entity = 'user';
        $entityId = $this->id;
        if (!$entity || !$entityId)
           return false;

        $tmp_path = Yii::getAlias('@webroot/tmp/'.$entity.'/'.$entityId);
        $tmp_url = '/tmp/'.$entity.'/'.$entityId;


        if (file_exists($tmp_path . DIRECTORY_SEPARATOR . self::AVATAR_NAME))
        {
            return $tmp_url . '/' . self::AVATAR_NAME;
        }
        else
        {
            $this->updateAvatar();
            if (file_exists($tmp_path . DIRECTORY_SEPARATOR . self::AVATAR_NAME))
            {
                return $tmp_url . '/' . self::AVATAR_NAME;
            }
        }

        return "http://www.gravatar.com/avatar/00000000000000000000000000000000?d=mm&f=y&s=50";
    }
}
