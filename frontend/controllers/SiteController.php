<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\User;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\SignupConfirm;
use frontend\models\ContactForm;
use frontend\models\Index;
use common\models\CenterSearch;
use common\models\ArendaSearch;
use common\models\EventSearch;
use common\models\NewsSearch;
use common\models\Region;
use common\models\Center;
use common\models\News;
use common\models\Station;
use frontend\components\RegionManager;
use yii\web\ForbiddenHttpException;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'profile', 'my'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['profile'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['my'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
			'eauth' => [
				// required to disable csrf validation on OpenID requests
				'class' => \nodge\eauth\openid\ControllerBehavior::className(),
				'only' => ['login'],
			],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex($region = null)
    {
        if ($region) {
            Yii::$app->regionManager->setRegion($region);
        }
        else {
            $region = Yii::$app->regionManager->id;
        }

        $this->layout = 'mainpage';

        $model = new Index();
        $model->region = $region;

        $searchModel1 = new CenterSearch();
        $centers = $searchModel1->searchForMainPage();

        $searchModel2 = new ArendaSearch();
        $arenda = $searchModel2->searchForMainPage();

        $searchModel3 = new NewsSearch();
        $lead = $searchModel3->searchLead();
        $other = $searchModel3->searchOther();

        $searchModel4 = new EventSearch();
        $events = $searchModel4->searchForMainPage();

        return $this->render('index', [
            'model' => $model,
            'centers' => $centers,
            'arenda' => $arenda,
            'events' => $events,
            'lead' => $lead,
            'other' => $other,
        ]);
    }

    public function actionIndexSubmit()
    {
        $type = Yii::$app->request->post('type');
        $region = Yii::$app->request->post('region');
        switch ($type)
        {
            case 1:
                return $this->redirect(['center/index', 'CenterSearch' => ['region' => $region]]);
            case 2:
                return $this->redirect(['arenda/index', 'ArendaSearch' => ['region' => $region]]);
        }
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
     public function actionLogin()
     {
        $serviceName = Yii::$app->getRequest()->getQueryParam('service');
        if (isset($serviceName))
        {
            $eauth = Yii::$app->get('eauth')->getIdentity($serviceName);
            $eauth->setRedirectUrl(Yii::$app->getUser()->getReturnUrl());
            $eauth->setCancelUrl(Yii::$app->getUrlManager()->createAbsoluteUrl('site/login'));

            try
            {
                if ($eauth->authenticate())
                {
                    $identity = User::findByEAuth($eauth);
					          Yii::$app->getUser()->login($identity);

                    // special redirect with closing popup window
                    $eauth->redirect();
                }
                else
                {
                    // close popup window and redirect to cancelUrl
                    $eauth->cancel();
                }
            }
            catch (\nodge\eauth\ErrorException $e)
            {
                // save error to show it later
                Yii::$app->getSession()->setFlash('error', 'EAuthException: '.$e->getMessage());

                // close popup window and redirect to cancelUrl
//              $eauth->cancel();
                $eauth->redirect($eauth->getCancelUrl());
            }
        }

        if (!Yii::$app->user->isGuest)
            return $this->goHome();

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login())
            return $this->goBack();
        else
            return $this->render('login', [ 'model' => $model ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
      public function actionLogout()
      {
          Yii::$app->user->logout();

          return $this->goHome();
      }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionProfile()
    {
       if (Yii::$app->user->isGuest)
          return $this->goHome();

       $model = User::findIdentity(Yii::$app->user->identity->id);
       return $this->render('profile', [
           'model' => $model,
       ]);
    }

    public function actionMy()
    {
       if (Yii::$app->user->isGuest)
          return $this->goHome();

       $searchModel = new ArendaSearch();
       $dataProvider = $searchModel->searchMy();

       return $this->render('my', [
           'searchModel' => $searchModel,
           'dataProvider' => $dataProvider,
       ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionContacts()
    {
        return $this->render('contacts');
    }

    public function actionAdv()
    {
        return $this->render('adv');
    }

    public function actionUpdatemetro()
    {
        return $this->render('updatemetro');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()))
        {
            if ($user = $model->signup())
            {
                Yii::$app->session->setFlash('success', 'На указанный адрес электронный почты выслано сообщение с дальнейшими инструкциями.');
                return $this->goHome();
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            if ($model->sendEmail())
            {
                Yii::$app->session->setFlash('success', 'На указанный адрес выслано сообщение с дальнейшими инструкциями.');
                return $this->redirect(['login']);
                //return $this->goHome();
            }
            else
            {
                Yii::$app->session->setFlash('error', 'Для указанного адреса восстановление пароля невозможно');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try
        {
            $model = new ResetPasswordForm($token);
        }
        catch (InvalidParamException $e)
        {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword())
        {
            Yii::$app->session->setFlash('success', 'Новый пароль успешно сохранен.');
            Yii::$app->getUser()->login($model->user);
            return $this->goHome();
            //return $this->redirect(['my']);
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionSignupConfirm($token)
    {
        try {
            $model = new SignupConfirm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->signupConfirm()) {
            Yii::$app->session->setFlash('success', 'Регистрация успешно подтверждена!');

            return $this->goHome();
        }

        Yii::$app->session->setFlash('danger', 'Ошибка при подтверждении регистрации!');

        return $this->goHome();
    }

    public function actionUpdateUser($id)
    {
        if (($model = User::findOne($id)) !== null)
        {
            if ($model->load(Yii::$app->request->post()) && $model->save())
                return $this->render('profile', ['model' => $model]);
            else
                return $this->render('profile', ['model' => $model]);
        }
        else
            return false;
    }

    public function actionMapParams($id)
    {
        if (($model = Region::findOne($id)) !== null)
        {
            header("Content-Type: application/json; charset=UTF-8");
            echo $model->getMapParams();
        }
     }

     public function actionStationsList($q = null, $id = null)
     {
         // Настройки формата:
         \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
         $out = ['results' => ['id' => '', 'text' => '']];

         // Результаты будут разные для разных регионов, поэтому смотрим на регион:
         $regionId = Yii::$app->regionManager->id ? Yii::$app->regionManager->id : 0;

         // Если есть подстрока для поиска:
         if (!is_null($q)) {
             $query = new \yii\db\Query;
             $query->select("id, name AS text")
                 ->from('station')
                 ->where(['like', 'name', $q])
                 ->andWhere(['region' => $regionId])
                 ->limit(10);
             $command = $query->createCommand();
             $data = $command->queryAll();
             $out['results'] = array_values($data);
         }
         elseif ($id > 0) {
             $out['results'] = ['id' => $id, 'text' => Station::find($id)->name];
         }
         //return json_encode($out);
         return $out;
     }

}
