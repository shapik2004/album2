<?php
namespace frontend\controllers;

use app\components\AlphaId;
use common\models\CartForm;
use common\models\Invoice;
use common\models\User;
use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\UserSetting;

/**
 * Site controller
 */
class BaseController extends Controller
{

    public function  beforeAction($action){


        //$css_file_id='';

        $ref = Yii::$app->request->get('ref', '');
        $id = Yii::$app->request->get('id', '');

        $user_id=0;
        if (!\Yii::$app->user->isGuest) {
            $user_id=\Yii::$app->user->identity->getId();
        }
        if(!empty($ref)){
            $user_id=AlphaId::id($ref, true);
        }


        $user_setting=UserSetting::findByUserId($user_id);


        if($user_setting){
            $this->getView()->params['css_file_id'] = $user_setting->css_file_id;
            $this->getView()->params['logo_url'] = $user_setting->logo_url;
            $this->getView()->params['ref_user_id'] = $user_id;

            $this->getView()->params['id']=$id;
            $this->getView()->params['ref']=$ref;

        }
        else
        {
            $this->getView()->params['css_file_id'] = '';
            $this->getView()->params['logo_url']='';
        }



        if($user_id!=0){

            $cartForm= new CartForm();


            $rows=$cartForm->getUserCart($user_id, true);



            $this->getView()->params['cart_count']=count($rows);

            $invoicies=Invoice::find()->where(['user_id'=>$user_id, 'status'=>Invoice::STATUS_NEW])->all();

            $invoice=Invoice::findOne(['user_id'=>$user_id, 'status'=>Invoice::STATUS_NEW]);

            $invoice_id=0;

            if($invoice)
            $invoice_id=$invoice->id;

            $this->getView()->params['invoice_count']=count($invoicies);



            $this->getView()->params['invoice_id']=($invoice_id);


            if(!Yii::$app->user->isGuest && Yii::$app->user && Yii::$app->user->identity)
            $this->getView()->params['demo']=(Yii::$app->user->identity->role==User::ROLE_DEMO) ? true: false;






        }else{

            $this->getView()->params['demo']=0;

        }




        return parent::beforeAction($action);

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




}
