<?php
namespace frontend\controllers;

use app\components\AlphaId;
use app\components\LiqPay;
use common\models\Invoice;
use common\models\InvoiceForm;
use common\models\SettingForm;
use Yii;

use yii\filters\AccessControl;

use common\models\Photobook;
use common\models\PhotobookForm;

use yii\helpers\Url;

use yii\data\Pagination;

use yii\data\Sort;

use common\models\Template;
use common\models\Cover;
use common\models\CoverForm;
use common\models\Font;
use yii\helpers\ArrayHelper;
/**
 * Site controller
 */
class LiqpayController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
           /* 'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'add', 'upload_photos'],
                'rules' => [
                    [
                        'actions' => ['index', 'add', 'upload_photos'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],

                ],
            ]*/
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


    public function beforeAction($action)
    {

        //$msg='TEST2:'.$action;
        //Yii::getLogger()->log('TEST2'.print_r($action, true), YII_DEBUG);
        if ($action->id == 'server-notify') {

            // Yii::getLogger()->log('TEST3', YII_DEBUG);
            $this->enableCsrfValidation = false;
        }



        return  parent::beforeAction($action);
    }

    public function actionPay()
    {
        $this->layout='layouts';


        $invoice_id= Yii::$app->request->get('id');


        if(!$invoice_id){

            $this->redirect(Url::toRoute(['photobooks/not-found']));

            return;
        }



        $invoice=new InvoiceForm();


        if(!$invoice->loadById($invoice_id)){


            $this->redirect(Url::toRoute(['photobooks/not-found']));

            return;

        }



        if($invoice->status!=Invoice::STATUS_NEW){

            $this->redirect(Url::toRoute(['photobooks/invoice', 'id'=>$invoice_id]));
            return;
        }


        $liqpay=new LiqPay();



        $settings=new SettingForm();


        $public_key=$settings->getValue('liqpay_public_key', null);


        $private_key=$settings->getValue('liqpay_private_key', null);


        if(empty($public_key) || empty($private_key)){
            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Liqpay не настроен.'));
            $this->redirect(Url::toRoute(['photobooks/invoice', 'id'=>$invoice_id]));
            return;
        }



        $liqpay->setKeys($public_key, $private_key);


        $order_data=[

            'version'=>3,
            'amount'=>$invoice->total,
            'currency'=>$invoice->currency,
            'description'=>Yii::t('app', 'Оплата счет-фактуры № {num}', ['num'=>$invoice->id]),
            'order_id'=>$invoice->id,
            'server_url'=>Url::toRoute(['liqpay/server-notify', 'id'=>$invoice_id], true),
            'result_url'=>Url::toRoute(['photobooks/invoice', 'id'=>$invoice_id], true),
            'pay_way'=>'card,liqpay,privat24',
            'language'=>'ru',
            'sandbox'=>1,

        ];


        $form=$liqpay->cnb_form($order_data);




        return $this->render('pay', ['form'=>$form]);

    }


    public function actionServerNotify(){


        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $invoice_id= Yii::$app->request->get('id');


        if(!$invoice_id){

            Yii::getLogger()->log('actionServerNotify return:'.Yii::t('app', 'Не верный id'), YII_DEBUG);
            return ['error'=>['msg'=>Yii::t('app', 'Не верный id')]];
        }


        $invoice=new InvoiceForm();


        if(!$invoice->loadById($invoice_id)){

            Yii::getLogger()->log('actionServerNotify return:'.Yii::t('app', 'Счет не найден'), YII_DEBUG);
            return ['error'=>['msg'=>Yii::t('app', 'Счет не найден')]];
        }



        $data=Yii::$app->request->post('data', null);


        $signature=Yii::$app->request->post('signature', null);


        if(empty($data) || empty($signature)){


            Yii::getLogger()->log('actionServerNotify return:'.Yii::t('app', 'Не полные данные'), YII_DEBUG);
            return ['error'=>['msg'=>Yii::t('app', 'Не полные данные')]];
        }


        $settings=new SettingForm();


        // $public_key=$settings->getValue('liqpay_public_key', null);


        $private_key=$settings->getValue('liqpay_private_key', null);

        if(empty($private_key)){

            Yii::getLogger()->log('actionServerNotify return:'.Yii::t('app', 'Liqpay не настроен.'), YII_DEBUG);

            return ['error'=>['msg'=>Yii::t('app', 'Liqpay не настроен.')]];
        }


        $sign=base64_encode( sha1( $private_key . $data . $private_key, 1 ) );




        Yii::getLogger()->log('actionServerNotify sign:'.$sign, YII_DEBUG);

        Yii::getLogger()->log('actionServerNotify signature:'.$signature, YII_DEBUG);


        if($sign!=$signature){


            Yii::getLogger()->log('actionServerNotify return:'.Yii::t('app', 'Подпись не верна'), YII_DEBUG);
            return ['error'=>['msg'=>Yii::t('app', 'Подпись не верна')]];
        }


        //Получаем статус платежа


        $data=json_decode(base64_decode($data), true);

        $liqpay_status=$data['status'];


        Yii::getLogger()->log('actionServerNotify data:'.print_r($data, true), YII_DEBUG);


        Yii::getLogger()->log('actionServerNotify liqpay_status:'.$liqpay_status, YII_DEBUG);


        if($liqpay_status=='sandbox' || $liqpay_status=='success' ){


            $invoice->status=Invoice::STATUS_PAID;


            if($invoice->save()){


                $photobooks=Photobook::find()->where(['invoice_id'=>$invoice->id])->all();

                $error=false;
                if($photobooks){

                    foreach($photobooks as $key=>$photobook){


                        $photobook->status=Photobook::STATUS_READY_FOR_PRINT_PAID;

                        $photobook->change_status_at=time();


                        if(!$photobook->update()){

                            $error=true;
                        }

                    }
                }

                if(!$error){

                    return ['response'=>['status'=>true]];
                }



            }else{


                return ['error'=>['msg'=>Yii::t('app', 'Не удалось обновить статус счета')]];

            }


        }else{


            return ['response'=>['status'=>true]];

        }






    }


    public function actionClientNotify(){



        $invoice_id= Yii::$app->request->get('id');

        if(!$invoice_id){

            $this->redirect(Url::toRoute(['photobooks/not-found']));

            return;
        }


        $invoice=new InvoiceForm();


        if(!$invoice->loadById($invoice_id)){


            $this->redirect(Url::toRoute(['photobooks/not-found']));

            return;

        }



        /*if($invoice->status==Invoice::STATUS_PAID){

            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Оплата получена'));

        }else{



        }*/


        $this->redirect(Url::toRoute(['photobooks/invoice', 'id'=>$invoice_id]));


    }







}
