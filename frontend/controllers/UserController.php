<?php
namespace frontend\controllers;


use common\models\SettingForm;
use common\models\User;
use common\models\UserSetting;
use frontend\models\UserSettingForm;
use Yii;
use common\models\LoginForm;

use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use app\components\UserUrl;
use app\components\AlphaId;
use frontend\models\ProfileForm;


/**
 * Site controller
 */
class UserController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'settings'],
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
                        'actions' => ['settings'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    /*'logout' => ['post'],*/
                ],
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

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {


        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout='login';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return  $this->redirect(Url::toRoute('user/login'));
    }

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

    public function actionAbout()
    {
        return $this->render('about');
    }


    public function actionSignupDemo()
    {

        if(Yii::$app->user->isGuest){

            $this->redirect(Url::toRoute(['user/login']));
            return;
        }
        $this->layout='login';

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {


            $model->id=Yii::$app->user->identity->getId();


            if ( $model->signupDemo()) {

                return $this->goHome();
            }
        }

        return $this->render('signupDemo', [
            'model' => $model,
        ]);
    }

    public function actionSignup()
    {

        $this->layout='login';

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $this->layout='login';

        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app','Проверьте вашу электронную почту для получения дальнейших инструкций.'));

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', Yii::t('app','К сожалению, мы не в состоянии сбросить пароль. Пожалуйста попробуйте позже... Если проблема будет повторятся, обратитесь в службу поддержки.'));
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        $this->layout='login';

        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionSettings(){


        if(Yii::$app->user->isGuest){

            $this->redirect(Url::toRoute(['intro/index']));
            return;

        }



        if(Yii::$app->user->identity->role==User::ROLE_DEMO){

            $this->redirect(Url::toRoute(['user/signup-demo']));
            return;

        }




        $this->layout='default';

        $less_content=file_get_contents(Yii::getAlias('@webroot').DIRECTORY_SEPARATOR.'less'.DIRECTORY_SEPARATOR.'pb-color-template.less');

        $model = new UserSettingForm();


        if($model->load(Yii::$app->request->post(), 'UserSettingForm')){

            if($model->validate()){

                $model->user_id=Yii::$app->user->identity->getId();
                $file = UploadedFile::getInstance($model, 'logo_url');

                if($file){


                    if($file->size!==0){
                        $model->logo_url=$file;
                    }else{
                        $model->logo_url=null;
                    }

                    if($file->size!==0){

                        $old_file_id='';
                        $user_setting=UserSetting::findByUserId($model->user_id);

                        if($user_setting){
                            $old_file_id=$user_setting->logo_url;
                        }

                        $file_id=AlphaId::id(rand(10000000000, 9999999999999));
                        $file_path=UserUrl::logo().DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, UserUrl::IMAGE_ORIGINAL, 'png');
                        $file->saveAs($file_path);

                        $image=Yii::$app->image->load($file_path);
                        $image->resize(0,49, Yii\image\drivers\Image::HEIGHT);
                        $image->save(UserUrl::logo().DIRECTORY_SEPARATOR . UserUrl::imageFile($file_id, UserUrl::IMAGE_SMALL, 'png'));

                        $model->logo_url=$file_id;

                        if(!empty($old_file_id)){
                            if(file_exists(UserUrl::logo().DIRECTORY_SEPARATOR . UserUrl::imageFile($old_file_id, UserUrl::IMAGE_ORIGINAL, 'png')))
                                unlink(UserUrl::logo().DIRECTORY_SEPARATOR . UserUrl::imageFile($old_file_id, UserUrl::IMAGE_ORIGINAL, 'png'));

                            if(file_exists(UserUrl::logo().DIRECTORY_SEPARATOR . UserUrl::imageFile($old_file_id, UserUrl::IMAGE_SMALL, 'png')))
                                unlink(UserUrl::logo().DIRECTORY_SEPARATOR . UserUrl::imageFile($old_file_id, UserUrl::IMAGE_SMALL, 'png'));
                        }
                    }
                }else{
                    $model->logo_url=null;
                }


                if(Yii::$app->request->post('defaultLogo', 0 )==1){


                    $model->logo_url='default-logo';

                }


                if($setting=$model->save()){

                    Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Настройки успешно сохранены.'));
                    return $this->redirect(Url::toRoute('user/settings'));
                }
            }
        }else{

            if(!$model->loadByUserId(Yii::$app->user->identity->getId())){


            }
        }

        return $this->render('settings', ['model'=>$model, 'less_content'=>$less_content]);
    }


    public function actionProfile(){


        if(Yii::$app->user->isGuest){

            $this->redirect(Url::toRoute(['intro/index']));
            return;

        }



        if(Yii::$app->user->identity->role==User::ROLE_DEMO){

            $this->redirect(Url::toRoute(['user/signup-demo']));
            return;

        }


        $this->layout='default';
        $model = new ProfileForm();

        if($model->load(Yii::$app->request->post(), 'ProfileForm')){


            if($model->validate()){

                if($model->save()){
                    Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Изменения успешно сохранены.'));
                    return $this->redirect(Url::toRoute('user/profile'));

                }else{



                }
            }else{



            }

        }else{

            if(!$model->loadByUserId(Yii::$app->user->identity->getId())){


            }
        }


        $settings=new SettingForm();


        $currencies=$settings->getValue('currencies', []);

        $currencies_new=[];

        foreach($currencies as $key=>$currency){

            $currencies_new[$currency['code']]=$currency['code'];

        }


        return $this->render('profile', ['model'=>$model, 'currencies'=>$currencies_new]);


    }
}
