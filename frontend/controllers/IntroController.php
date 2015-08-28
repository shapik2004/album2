<?php
namespace frontend\controllers;

use app\components\AlphaId;
use app\components\UserUrl;
use Yii;

use yii\filters\AccessControl;

use common\models\Photobook;
use common\models\PhotobookForm;

use yii\helpers\Url;

use yii\data\Pagination;

use yii\data\Sort;

use common\models\Style;
use common\models\Template;
use common\models\User;
use common\models\LoginForm;
/**
 * Site controller
 */
class IntroController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['add', 'upload_photos'],
                'rules' => [
                    [
                        'actions' => ['index', 'add', 'upload_photos'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],

                ],
            ]
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


        if (\Yii::$app->user->isGuest) {
            $model = new LoginForm();
            if($model->demoLogin()){

                return $this->goHome();
            }else{

                return $this->redirect(Url::toRoute('user/login'));
            }
        }

       // $this->layout='login';






        // User::
        $this->layout='intro';
        return $this->render('index',[]);

    }





}
