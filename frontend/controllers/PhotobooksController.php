<?php
namespace frontend\controllers;

use app\components\AlphaId;
use app\components\CurrencyConvertor;
use app\components\UserUrl;
use common\components\Utils;
use common\models\Cart;
use common\models\CartForm;
use common\models\Cover;
use common\models\CoverForm;
use common\models\Invoice;
use common\models\InvoiceForm;
use common\models\PhotobookState;
use common\models\SettingForm;
use common\models\StyleForm;
use common\models\User;
use Yii;

use yii\filters\AccessControl;

use common\models\Photobook;
use common\models\PhotobookForm;

use yii\helpers\Url;

use yii\data\Pagination;

use yii\data\Sort;

use common\models\Style;
use common\models\Template;
use yii\helpers\BaseUrl;
/**
 * Site controller
 */
class PhotobooksController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'add', 'upload_photos'],
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
        $this->layout='default';


        if(Yii::$app->user->isGuest){

            $this->redirect(Url::toRoute(['intro/index']));
            return;

        }



        if(Yii::$app->user->identity->role==User::ROLE_DEMO){

            $this->redirect(Url::toRoute(['user/signup-demo']));
            return;

        }



        $status = Yii::$app->request->get('status', Photobook::STATUS_NEW);

        if($status==Photobook::STATUS_DEMO){

            $this->redirect(Url::toRoute(['photobooks/index']));
            return;
        }


        $user_id=Yii::$app->user->identity->getId();


      //  $photobooks=new Photobook();

        $sort = new Sort([
             'attributes' => [
                 'updated_at'=>SORT_ASC,
                 /*'name' => [
                     'asc' => ['first_name' => SORT_ASC, 'last_name' => SORT_ASC],
                     'desc' => ['first_name' => SORT_DESC, 'last_name' => SORT_DESC],
                     'default' => SORT_DESC,
                     'label' => 'Name',
                 ],*/
             ],
         ]);

        $query = Photobook::find()->where(['user_id'=>$user_id, 'status' => $status]);
        $countQuery = clone $query;

        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $orders = $query->orderBy([ 'updated_at'=>SORT_DESC])->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        /*
         *
         *   const  STATUS_NEW = 1;
    const  STATUS_SENT_TO_CUSTOMER=2;
    const  STATUS_WAIT_EDIT_FROM_CUSTOMER=3;
    const  STATUS_SENT_TO_PRINT=4;
    const  STATUS_READY_FOR_PRINT_WAIT_PAYMENT=5;
    const  STATUS_READY_SENT_TO_PRINT=6;
    const  STATUS_READY=7;
    const  STATUS_SENT_TO_CLIENT=8;
    const  STATUS_RECEIVED_FEEDBACK=9;
    const  STATUS_ARCHIVE = 10;
         */

        $sidemenus=[
            [
                'title'=>Yii::t('app', 'Новые заказы'),
                'status'=>Photobook::STATUS_NEW,
                'count'=>Photobook::find()->where(['user_id'=>$user_id, 'status'=>Photobook::STATUS_NEW])->count()
            ],
            [
                'title'=>Yii::t('app', 'Отправленные на согласование с клиентом'),
                'status'=>Photobook::STATUS_SENT_TO_CUSTOMER,
                'count'=>Photobook::find()->where(['user_id'=>$user_id, 'status'=>Photobook::STATUS_SENT_TO_CUSTOMER])->count()
            ],
            [
                'title'=>Yii::t('app', 'Ожидающие правок'),
                'status'=>Photobook::STATUS_WAIT_EDIT_FROM_CUSTOMER,
                'count'=>Photobook::find()->where(['user_id'=>$user_id, 'status'=>Photobook::STATUS_WAIT_EDIT_FROM_CUSTOMER])->count()
            ],
            [
                'title'=>Yii::t('app', 'Утвержденные клиентом'),
                'status'=>Photobook::STATUS_SENT_TO_PRINT,
                'count'=>Photobook::find()->where(['user_id'=>$user_id, 'status'=>Photobook::STATUS_SENT_TO_PRINT])->count()
            ],
            [
                'title'=>Yii::t('app', 'Готовы к печати, ожидает оплаты'),
                'status'=>Photobook::STATUS_READY_FOR_PRINT_WAIT_PAYMENT,
                'count'=>Photobook::find()->where(['user_id'=>$user_id, 'status'=>Photobook::STATUS_READY_FOR_PRINT_WAIT_PAYMENT])->count()
            ],
            [
                'title'=>Yii::t('app', 'Готовы к печати, оплачено'),
                'status'=>Photobook::STATUS_READY_FOR_PRINT_PAID,
                'count'=>Photobook::find()->where(['user_id'=>$user_id, 'status'=>Photobook::STATUS_READY_FOR_PRINT_PAID])->count()
            ],
            [
                'title'=>Yii::t('app', 'В производстве'),
                'status'=>Photobook::STATUS_READY_SENT_TO_PRINT,
                'count'=>Photobook::find()->where(['user_id'=>$user_id, 'status'=>Photobook::STATUS_READY_SENT_TO_PRINT])->count()
            ],
            [
                'title'=>Yii::t('app', 'Готовые'),
                'status'=>Photobook::STATUS_READY,
                'count'=>Photobook::find()->where(['user_id'=>$user_id, 'status'=>Photobook::STATUS_READY])->count()
            ],
            [
                'title'=>Yii::t('app', 'Отправленные'),
                'status'=>Photobook::STATUS_READY_SENT_TO_CLIENT,
                'count'=>Photobook::find()->where(['user_id'=>$user_id, 'status'=>Photobook::STATUS_READY_SENT_TO_CLIENT])->count()
            ],
            [
                'title'=>Yii::t('app', 'Полученные'),
                'status'=>Photobook::STATUS_RECEIVED_FEEDBACK,
                'count'=>Photobook::find()->where(['user_id'=>$user_id, 'status'=>Photobook::STATUS_RECEIVED_FEEDBACK])->count()
            ],
            [
                'title'=>Yii::t('app', 'Архив'),
                'status'=>Photobook::STATUS_ARCHIVE,
                'count'=>Photobook::find()->where(['user_id'=>$user_id, 'status'=>Photobook::STATUS_ARCHIVE])->count()
            ]
        ];



        $settingForm=new SettingForm();


        $photobook_thumb_as_object= $settingForm->getValue('photobook_thumb_as_object', false);



        return $this->render('index', ['pages'=>$pages, 'orders'=>$orders, 'status'=>$status, 'sidemenus'=>$sidemenus, 'photobook_thumb_as_object'=>$photobook_thumb_as_object]);
    }

    public function actionDemo(){


        //Нужно найти проект с пометкой демо
        //Создать на основе него копию
        //И сделать редирект на его edit

        if(Yii::$app->user->isGuest){

            $this->redirect(Url::toRoute(['intro/index']));
            return;

        }



        $photobooks=Photobook::find()->where(['user_id'=>Yii::$app->user->identity->getId(), 'status'=>Photobook::STATUS_DEMO])->all();


        if(count($photobooks)>0){

            $photobook=$photobooks[0];



            $this->redirect(Url::toRoute(['photobooks/edit', 'ref'=>AlphaId::id($photobook->user_id), 'id'=>AlphaId::id($photobook->id)]));
            return;


        }


        $settingForm=new SettingForm();


        $demo_account_id=$settingForm->getValue('demo_account_id', 0);

        $photobooks=Photobook::find()->where(['user_id'=>$demo_account_id, 'status'=>Photobook::STATUS_NEW])->all();


        if(count($photobooks)>0){


            $photobook=$photobooks[0];


            $photobookForm=new PhotobookForm();



            if($photobookForm->loadById($photobook->id)){


                $result=$photobookForm->copyToUser(Yii::$app->user->identity->getId(), Photobook::STATUS_DEMO);



                if(!empty($result['response'])){


                    $pb_id=$result['response']['id'];
                    $user_id=$result['response']['user_id'];


                    $this->redirect(Url::toRoute(['photobooks/edit', 'ref'=>AlphaId::id($user_id), 'id'=>AlphaId::id($pb_id)]));


                    return;



                }else{


                    $this->redirect(Url::toRoute(['photobooks/index']));
                    return;
                }




            }else{


                $this->redirect(Url::toRoute(['photobooks/index']));
                return;

            }


        }else{


            $this->redirect(Url::toRoute(['photobooks/index']));
            return;

        }
    }

    public function actionAdd(){

        $this->layout='default';


        $model=new PhotobookForm();

        $ref = Yii::$app->request->get('ref');


        if(!empty($ref)){

            $user_id=AlphaId::id($ref, true);

            $model->user_id=$user_id;

            $model->name='Новая книга';
            $model->status=Photobook::STATUS_NEW;
            $model->data='';
            $model->template='';

            if(empty($model->photos)){

                //Забиваем группа по умолчанию

                //$this->id=$pb_id;
                $model->photos=['Утро невесты'=>['photos'=>[], 'reversals'=>3], 'Прогулка'=>['photos'=>[], 'reversals'=>3], 'Текст'=>['type'=>'text', 'photos'=>[],'texts'=>[], 'reversals'=>3]];
               // Yii::getLogger()->log('Забиваем группа по умолчанию:'.$this->id.' | pb_id:'.$pb_id.' '.print_r($model->photos, true), YII_DEBUG);

            }


            $styles=Style::find()->where(['delete'=>0, 'status'=>1])->all();


            if(empty($styles)){

                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Не удалось создать новую фотокнигу. Так как нет ни одного опубликованого стиля.'));

                $this->redirect(Url::toRoute(['photobooks/index']));

                return;
            }

            $model->style_id=$styles[0]->id;




            $covers=Cover::find()->where(['status'=>1])->all();

            if(empty($covers)){

                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Не удалось создать новую фотокнигу. Так как нет ни одной опубликованой обложки.'));

                $this->redirect(Url::toRoute(['photobooks/index']));

                return;
            }

            $cover_index=0;

            foreach($covers as $key=>$cover){

                if($cover->default==1){

                    $cover_index=$key;
                    break;
                }
            }

            $model->cover_id=$covers[$cover_index]->id;


            $photobook=$model->save();
            if($photobook){

                $this->redirect(Url::toRoute(['photobooks/upload-photos', 'ref'=> $ref, 'id'=>AlphaId::id($photobook->id)]));
            }else{

                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Не удалось сохранить фотокнигу.'));

                $this->redirect(Url::toRoute(['photobooks/index']));
            }

        }else{

            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Не удалось создать новую фотокнигу.'));

            $this->redirect(Url::toRoute(['photobooks/index']));
        }

        return;

        //return $this->render('add', ['model'=>$model]);

    }



    public function actionUploadPhotos(){

        // [ 'Группа 1'=>['photo1', 'photo2', 'photo3'], 'Группа 2'=>['photo1', 'photo2', 'photo3'], 'Группа 3'=>['photo1', 'photo2', 'photo3'] ]

        $this->layout='uploads';
        $ref = Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');
        $selected_style_id=  Yii::$app->request->get('style_id', 0);


        if(!empty($ref) && !empty($id)){

            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);


            $model=new PhotobookForm();


            if(!$model->loadById($pb_id)){

                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Фотокнига не найдена'));

                $this->redirect(Url::toRoute(['photobooks/index']));

                return;
            }


            $styles=Style::find()->where(['delete'=>0, 'status'=>1])->all();


            if($selected_style_id==0){
                $selected_style_id=$styles[0]->id;
            }


            $settings=new SettingForm();


            $note_upload_page=$settings->getValue('note_upload_page', '');


            return $this->render('uploadPhotos', ['model'=>$model, 'ref'=>$ref, 'id'=>$id, 'pb_id'=>$pb_id, 'user_id'=>$user_id, 'styles'=>$styles, 'selected_style_id'=>$selected_style_id, 'note_upload_page'=>$note_upload_page]);

        }else{

            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Страница не найдена'));

            $this->redirect(Url::toRoute(['photobooks/index']));
        }

    }

    public function actionCheckout(){

        $this->layout='layouts';

        if(Yii::$app->user->isGuest){
            $this->redirect(Url::toRoute(['photobooks/not-found']));
            return;
        }


        $ref = Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');

        $back = Yii::$app->request->get('back', 'index');

        $user_id=Yii::$app->user->identity->getId();

        $user=User::findOne(['id'=>Yii::$app->user->identity->getId()]);
        $course=CurrencyConvertor::getCurse($user->default_currency);


        if(!empty($ref) && !empty($id)){


            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);


            $model=new PhotobookForm();


            if(!$model->loadById($pb_id)){

                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Фотокнига не найдена'));

                $this->redirect(Url::toRoute(['photobooks/not-found']));
                return;
            }




            $pages=$model->data['pages'];
            $print_count=0;
            foreach($pages as $key=>$page){

                if($key==0 || $key==count($pages)-1)  continue;

                if($page['action']=='print'){

                    $print_count++;

                }
            }







            $rows=CartForm::getUserCart($user_id);

            $selected_row=null;


            foreach($rows as $key=>$row){


                if($row->product_type==Cart::PRODUCT_PHOTOBOOK){

                    $row->product_info=json_decode( $row->product_info,true);


                    if(!empty($row->product_info) && !empty($row->product_info['Photobook']) && $row->product_info['Photobook']['id']==$pb_id){

                        $selected_row=$row;
                    }

                }

            }


            $productInfo=$model->getProductInfo();


            if(!empty($productInfo['error'])){


                Yii::$app->getSession()->setFlash('error',$productInfo['error']['msg']);

                $this->redirect(Url::toRoute(['photobooks/index']));

                return;

            }


            $productInfo=$productInfo['response'];


            $cartForm=new CartForm();


            if($selected_row){
                $cartForm->loadById($selected_row->id);
            }else{
                $cartForm->quantity=1;
                $cartForm->user_id=$user_id;
            }


            $cartForm->product_type=Cart::PRODUCT_PHOTOBOOK;
            $cartForm->title=$productInfo['text_info'];
            $cartForm->price=$productInfo['price'];

            if($cartForm->quantity<=0){
                $cartForm->quantity=1;
            }
            $cartForm->sub_total=$cartForm->price*$cartForm->quantity;

            $cartForm->product_info=['Photobook'=>['id'=>$pb_id]];


            if(!$model->setStatus(Photobook::STATUS_READY_FOR_PRINT_WAIT_PAYMENT)){

                //Под вопросом остановка скрипта
            }



            if(!$cartForm->save()){


                Yii::$app->getSession()->setFlash('error',"Не удалось записать в базу данных");

                $this->redirect(Url::toRoute(['photobooks/index']));

                return;

            }else{


                $this->redirect(Url::toRoute(['photobooks/checkout']));

                return;

            }

        }

        $cart_rows=CartForm::getUserCart($user_id);

        return $this->render('checkout', [   'user_id'=>$user_id, 'cart_rows'=>$cart_rows, 'course'=>$course, 'default_currency'=>$user->default_currency, 'back'=>$back]);



       /* {

            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Фотокнига не найдена'));
            $this->redirect(Url::toRoute(['photobooks/index']));
        }*/
    }


    public function actionCheckout2(){

        $this->layout='layouts';

        if(Yii::$app->user->isGuest){
            $this->redirect(Url::toRoute(['photobooks/not-found']));
            return;
        }


        $ref = Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');

        $user_id=Yii::$app->user->identity->getId();

        $user=User::findOne(['id'=>Yii::$app->user->identity->getId()]);
        $course=CurrencyConvertor::getCurse($user->default_currency);




        $cart_rows=CartForm::getUserCart($user_id, true, $course);


        if(count($cart_rows)==0){


            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Корзина пуста. Вы не можите создать счет.'));
            $this->redirect(Url::toRoute(['photobooks/index']));
        }

        $total=0;


        //Считаем сумму

        foreach($cart_rows as $key=>$cart_row){

            $total+=$cart_row['sub_total'];

        }



        $invoice=new InvoiceForm();

        $invoice->user_id=$user_id;

        $invoice->data=['rows'=>$cart_rows];

        $invoice->currency=$user->default_currency;

        $invoice->payment_type=Invoice::TYPE_LIQPAY;

        $invoice->delivery_address=$user->delivery_address;

        $invoice->total=$total;

        $invoice->status=Invoice::STATUS_NEW;


        if(!$invoice->save()){


            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Ну удалось записать в базу данных.'));
            $this->redirect(Url::toRoute(['photobooks/index']));
            return;

        }

        //Имеем $invoice->id
        //Нужно его привязать к фотокнигам

        foreach($cart_rows as $key=>$cart_row){

            $total+=$cart_row['sub_total'];

            if($cart_row['product_type']==Cart::PRODUCT_PHOTOBOOK && !empty($cart_row['product_info']) && !empty($cart_row['product_info']['Photobook']) ){

                $pb_id=$cart_row['product_info']['Photobook']['id'];

                $photobook=new PhotobookForm();

                if($photobook->loadById($pb_id)){

                    $photobook->invoice_id=$invoice->id;

                    $photobook->save();
                }


            }

        }

        //Чистим корзину
        Cart::deleteAll(['user_id'=>$user_id]);


        //Отправляем пользователя на счет

        $this->redirect(Url::toRoute(['photobooks/invoice', 'id'=>$invoice->id]));



        //return $this->render('checkout', [   'user_id'=>$user_id, 'cart_rows'=>$cart_rows, 'course'=>$course, 'default_currency'=>$user->default_currency]);
    }

    public function actionCancelInvoice(){


        if(Yii::$app->user->isGuest){

            $this->redirect(Url::toRoute(['intro/index']));
            return;

        }



        if(Yii::$app->user->identity->role==User::ROLE_DEMO){

            $this->redirect(Url::toRoute(['user/signup-demo']));
            return;

        }



        $id=Yii::$app->request->get('id');


        if(!$id){


            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Не верный id'));
            $this->redirect(Url::toRoute(['photobooks/index']));

            return;

        }


        $invoice=new InvoiceForm();

        if(!$invoice->loadById($id)){

            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Счет не найден'));
            $this->redirect(Url::toRoute(['photobooks/index']));

            return;
        }



        if($invoice->status==Invoice::STATUS_PAID || $invoice->status==Invoice::STATUS_TIMEOUT  || $invoice->status==Invoice::STATUS_CANCEL){

            if($invoice->status==Invoice::STATUS_PAID) {
                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Счет уже оплачен. Вы не можите его отменить.'));
            }else if($invoice->status==Invoice::STATUS_CANCEL){

                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Счет уже отменен.'));
            }else if($invoice->status==Invoice::STATUS_TIMEOUT){

                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Счет уже отменен.'));
            }


            $this->redirect(Url::toRoute(['photobooks/index']));
        }



        $invoice->status=Invoice::STATUS_CANCEL;



        if(!$invoice->save()){

            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Не удалось отменить счет'));
            $this->redirect(Url::toRoute(['photobooks/index']));
            return;
        }


        // Отвязываем фотокниги от id счета и вернуть им статус new


        $photobooks=Photobook::find()->where(['invoice_id'=>$invoice->id])->all();



        if($photobooks){


            foreach($photobooks as $key=>$photobook){


                $photobook->invoice_id=null;
                $photobook->status=Photobook::STATUS_NEW;
                $photobook->update();

            }
        }







        $this->redirect(Url::toRoute(['photobooks/invoice', 'id'=>$invoice->id]));





    }

    public function actionGetInvoice(){

        //http://photobook/photobooks/get-invoice?pb_id=100000130
        $pb_id=Yii::$app->request->get('pb_id');


        if(Yii::$app->user->isGuest){

            $this->redirect(Url::toRoute(['photobooks/not-found']));
            return;
        }

        if(!$pb_id){

            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Не верный id'));
            $this->redirect(Url::toRoute(['photobooks/index']));
            return;
        }


        $photobook=new PhotobookForm();

        if(!$photobook->loadById($pb_id)){

            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Фотокнига не найдена'));
            $this->redirect(Url::toRoute(['photobooks/index']));
            return;
        }


        if(Yii::$app->user->identity->role!=User::ROLE_ADMIN){

            if($photobook->user_id!=Yii::$app->user->identity->user_id){

                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Фотокнига не найдена'));
                $this->redirect(Url::toRoute(['photobooks/index']));
                return;
            }
        }


        if($photobook->invoice_id){

            $invoice=new InvoiceForm();


            if($invoice->loadById($photobook->invoice_id)){


                $this->redirect(Url::toRoute(['photobooks/invoice', 'id'=>$invoice->id]));
                return;

            }else{


                //Зафиксим данные в базе

                $photobook->invoice_id=null;

                $photobook->save();

            }


        }



        //Если со счетом проблема
        //Смотрим корзину


        $cart_rows=CartForm::getUserCart(Yii::$app->user->identity->getId());


        //Нужно отыскать в ней нашу фотокнигу
        $our_photobook_exists=false;

        foreach($cart_rows as $key=>$row){
            if($row->product_type==Cart::PRODUCT_PHOTOBOOK && !empty($row->product_info) && !empty($row->product_info['Photobook'])  && $row->product_info['Photobook']['id']==$pb_id){

               $our_photobook_exists=true;
                break;
            }
        }


        //Если есть отправляем в корзину
        if($our_photobook_exists){

            $this->redirect(Url::toRoute(['photobooks/checkout']));

        }else{

            //Если не, то добовляем отправляем в корзину
            $ref=AlphaId::id($photobook->user_id);
            $id=AlphaId::id($photobook->id);

            $this->redirect(Url::toRoute(['photobooks/checkout', 'ref'=>$ref,  'id'=>$id]));

        }



    }


    public function actionInvoice(){

        $this->layout='layouts';

        /*if(Yii::$app->user->isGuest){
            $this->redirect(Url::toRoute(['photobooks/not-found']));
            return;
        }*/


        $id= Yii::$app->request->get('id');



        $invoice=new InvoiceForm();



        if(!$invoice->loadById($id)){

            $this->redirect(Url::toRoute(['photobooks/not-found']));
            return;

        }


        //Это нужно перенести в базу
        $payment_types=[

            Invoice::TYPE_LIQPAY=>[
                'online'=>true,
                'title'=>Yii::t('app', 'Кредитной картой')
            ],
            Invoice::TYPE_CASH=>[
                'online'=>false,
                'title'=>Yii::t('app', 'Наличными')
            ]
        ];




        return $this->render('invoice', [   'invoice'=>$invoice, 'payment_types'=>$payment_types]);
    }



    public function actionInvoicePay(){

        $this->layout='layouts';

        /*if(Yii::$app->user->isGuest){
            $this->redirect(Url::toRoute(['photobooks/not-found']));
            return;
        }*/


        $id= Yii::$app->request->get('id');

        $payment_type= Yii::$app->request->get('payment_type', Invoice::TYPE_LIQPAY);


        if($payment_type!=Invoice::TYPE_LIQPAY && $payment_type!=Invoice::TYPE_CASH){

            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Выбран не известный способ оплаты'));
            $this->redirect(Url::toRoute(['photobooks/invoice', 'id'=>$id]));
            return;
        }

        //Когда типы оплат будут в базе нужно проверять еще выбран ли онлайн или офлайн способ оплаты, если офлайн то генерить ошибку



        $invoice=new InvoiceForm();



        if(!$invoice->loadById($id)){

            $this->redirect(Url::toRoute(['photobooks/not-found']));
            return;

        }


        if($invoice->status==Invoice::STATUS_PAID || $invoice->status==Invoice::STATUS_CANCEL || $invoice->status==Invoice::STATUS_TIMEOUT){



            //Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Счет не действительный для оплаты'));
            //Может и нужно это

            $this->redirect(Url::toRoute(['photobooks/invoice', 'id'=>$invoice->id]));
            return;


        }


        //Ну тут нам нужно перейти на оплату через  выбранный способ


        if($payment_type==Invoice::TYPE_LIQPAY){



            $allow_currencies=['USD', 'EUR', 'RUB', 'UAH'];



            if(!in_array($invoice->currency, $allow_currencies )){

                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Извените, но валюта счета не поддерживается данным методом оплаты. Попробуйте выбрать другой метод оплаты.'));

                $this->redirect(Url::toRoute(['photobooks/invoice', 'id'=>$invoice->id]));
                return;

            }





            $this->redirect(Url::toRoute(['liqpay/pay', 'id'=>$invoice->id]));


            return;




        }


        Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Выбран не известный способ оплаты. Пожалуйста попробуйте еще раз. Если проблема будет повторяться свяжитесь с нами.'));

        $this->redirect(Url::toRoute(['photobooks/invoice', ['id'=>$invoice->id]]));




        //return $this->render('invoice', [   'invoice'=>$invoice, 'payment_types'=>$payment_types]);
    }



    public function actionSendToPrint(){

        if(Yii::$app->user->isGuest){

            $this->redirect(Url::toRoute(['intro/index']));
            return;

        }



        if(Yii::$app->user->identity->role==User::ROLE_DEMO){

            $this->redirect(Url::toRoute(['user/signup-demo']));
            return;

        }


        $this->layout='layouts';
        $ref = Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');

        $back = Yii::$app->request->get('back', 'index');

        if(!empty($ref) && !empty($id)){


            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);


            $model=new PhotobookForm();


            if(!$model->loadById($pb_id)){

                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Фотокнига не найдена'));

                $this->redirect(Url::toRoute(['photobooks/index']));

                return;
            }

            if(empty($model->data['pages'])){

                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Вы не можите отправить фотокнигу в печать, т.к в ней нет ни одного разворота. Закончите вашу книгу и повторите попытку.'));

                $this->redirect(Url::toRoute(['photobooks/index']));

                return;
            }


            $cover=new CoverForm();


            if(!$cover->loadById( $model->cover_id)){

                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Обложка для книги не найдена'));

                $this->redirect(Url::toRoute(['photobooks/index']));

                return;
            }







            $pages=$model->data['pages'];
            $print_count=0;
            foreach($pages as $key=>$page){

                if($key==0 || $key==count($pages)-1)  continue;

                if($page['action']=='print'){

                    $print_count++;

                }

            }



            return $this->render('sendToPrint', ['model'=>$model, 'cover'=>$cover, 'ref'=>$ref, 'id'=>$id, 'pb_id'=>$pb_id, 'user_id'=>$user_id, 'pages'=>$model->data['pages'], 'print_count'=>$print_count, 'back'=>$back]);




        }else{

            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Фотокнига не найдена'));
            $this->redirect(Url::toRoute(['photobooks/index']));
        }
    }



    public function actionLayouts(){



        $this->layout='layouts';
        $ref = Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');


        //$selected_style_id=  Yii::$app->request->get('style_id', 0);
        $reset=  Yii::$app->request->get('reset', 0);


        if(!empty($ref) && !empty($id)){

            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);


            $model=new PhotobookForm();


            if(!$model->loadById($pb_id)){

                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Фотокнига не найдена'));

                $this->redirect(Url::toRoute(['photobooks/index']));

                return;
            }


            //$styles=Style::find()->where(['delete'=>0])->all();




            if(empty($model->data))
                $model->data=[];

            if(empty($model->data['processed']))
                $model->data['processed']=[];

            if($reset==1)
            {

                $pages=$model->generatePages($model->style_id);

                if(empty($pages)){

                    Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Необходимо загрузить как минимум по одному фото в группу'));

                    $this->redirect(Url::toRoute(['photobooks/upload-photos', 'id'=>$id, 'ref'=>$ref]));

                    return;
                }


                $model->data['pages']=$pages;

            }else{

                $pages=$model->updatePages($model->style_id);

                if(empty($pages))
                {


                    Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Необходимо загрузить как минимум по одному фото в группу'));

                    $this->redirect(Url::toRoute(['photobooks/upload-photos', 'id'=>$id, 'ref'=>$ref]));
                }

                $model->data['pages']=$pages;
            }

            //$model->style_id=$selected_style_id;

            if(!$model->save()){
                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Не удалось сохранить'));
                $this->redirect(Url::toRoute(['photobooks/index']));
            }


           /* if($reset==1){

                $this->redirect(Url::toRoute(['photobooks/layouts', 'ref'=>$ref, 'id'=>$id]));
            }*/


            $photos=[];
            $groups=$model->photos;

            foreach($groups as $group_name=>$group){

                foreach($group['photos'] as $key=>$photo_id){

                    $mtime=UserUrl::photobookPhotos(false, $model->id, $model->user_id).DIRECTORY_SEPARATOR.UserUrl::imageFile($photo_id, UserUrl::IMAGE_THUMB);
                    $photos[]=['photo_id'=>$photo_id, 'mtime'=>$mtime];

                }
            }

            //$model->data['processed']

            return $this->redirect(Url::toRoute(['photobooks/edit', 'ref'=>$ref, 'id'=>$id]));

           // return $this->render('layouts', ['model'=>$model, 'ref'=>$ref, 'id'=>$id, 'pb_id'=>$pb_id, 'user_id'=>$user_id, 'pages'=>$pages, 'styles'=>$styles, 'photos'=>$photos, 'processed'=>$model->data['processed']]);

        }else{

            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Страница не найдена'));

            $this->redirect(Url::toRoute(['photobooks/index']));
        }

    }



    public function actionPageSvgThumb(){



       // $pid = pcntl_fork();


        $settingForm=new SettingForm();



        $photobook_thumb_as_object=$settingForm->getValue('photobook_thumb_as_object', false);


        if($photobook_thumb_as_object) {

            header('Content-type: image/svg+xml');
        }


        $ref = Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');
        $page_index= Yii::$app->request->get('page');

        $this->layout='empty';

        if(!empty($ref) && !empty($id)){

            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);

            $model=new PhotobookForm();

            if($model->loadById($pb_id)){


                $styleForm=new StyleForm();

                $style_background_image_base64='';


                if($styleForm->loadById($model->style_id)){


                    $padded_padded_passepartout_path=UserUrl::stylePaddedPassepartout(false,  $styleForm->id).DIRECTORY_SEPARATOR. UserUrl::imageFile($styleForm->padded_passepartout_key, UserUrl::IMAGE_THUMB);


                    $padded_padded_passepartout_data=file_get_contents($padded_padded_passepartout_path);

                    $type ='jpg';

                    $style_background_image_base64 = 'data:image/' . $type . ';base64,' . base64_encode($padded_padded_passepartout_data);

                }


                $coverForm=new CoverForm();


                $coverForm->loadById($model->cover_id);




                $padded_cover_image_path=UserUrl::coverPadded(false,$coverForm->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($coverForm->padded_cover, UserUrl::IMAGE_THUMB, 'png');


                $padded_cover_data=file_get_contents($padded_cover_image_path);


                $type ='png';

                $padded_cover_base64 = 'data:image/' . $type . ';base64,' . base64_encode($padded_cover_data);




                if(!empty($model->data['pages'][$page_index])){

                    $page=$model->data['pages'][$page_index];


                    $svg= '<svg version="1.1"
             xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:a="http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/"
             x="0" y="0" width="710"  height="260"  preserveAspectRatio="none"
             overflow="visible"  xml:space="preserve" viewBox="0 0 710 260">

    <svg width="100%" height="100%" x="0" y="0" viewBox="0 0 710 260" >

        <image xlink:href="'.$padded_cover_base64.'" x="0" y="0" height="260px" width="710px"/>

        <image xlink:href="'.$style_background_image_base64.'" x="5" y="5" height="250px" width="700px"/>
    </svg>





    <svg width="700" height="250" x="5" y="5" viewBox="0 0 700 250">
        '.str_replace('<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">', '', str_replace('<'.'?xml version="1.0" encoding="UTF-8" standalone="no"?'.'>', '',$page['svg_thumb'])).'
    </svg>
</svg>';


                    if($photobook_thumb_as_object) {

                        echo $svg;
                    }else{



                        $file_id=md5($svg);

                        $thumb_path=UserUrl::photobookPageThumb(false,$model->id, $model->user_id );//UserUrl::imageFile($file_id, UserUrl::IMAGE_THUMB);

                        $thumb_path_file=$thumb_path.DIRECTORY_SEPARATOR.$file_id.'.jpg';

                        $svg_path_file=$thumb_path.DIRECTORY_SEPARATOR.$file_id.'.svg';

                        $headers = Yii::$app->response->headers;

                        if(file_exists($thumb_path_file)){



                            $headers->add('Content-type','image/jpeg');
                            $headers->add('Cache-Control',"max-age=".(60*60*24));
                            $values=$headers->remove('Pragma');


                           // Yii::$app->response->headers=$values;


                            $content=file_get_contents($thumb_path_file);


                            Yii::$app->response->content=$content;

                            //Yii::$app->response->

                            Yii::$app->response->send();




                        }else{




                            $svg=str_replace('fill: transparent;', 'fill-opacity:0;', $svg);

                            $svg=str_replace('fill="transparent"', 'style="fill-opacity:0;"', $svg);

                            $svg=str_replace('<rect/>', '', $svg);

                            file_put_contents($svg_path_file, $svg);


                            $batik_path=Yii::getAlias('@app').DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'batik'.DIRECTORY_SEPARATOR;

                            // $cmds[]="java -d64 -Xms512m -Xmx4g -jar ".$batik_path."batik-rasterizer.jar -m image/jpg -w 350 -h 125 -q 0.99 -dpi 72 -d ".$png_path." ".$svg_path;


                            Yii::getLogger()->log('batik:'."java -d64 -Xms512m -Xmx4g -jar ".$batik_path."batik-rasterizer-1.8.jar -m image/jpg -w 350 -h 125 -q 0.65 -dpi 72 -d ".$thumb_path." ".$svg_path_file, YII_DEBUG);


                            exec("java -d64 -Xms512m -Xmx4g -jar ".$batik_path."batik-rasterizer-1.8.jar -m image/jpg -w 350 -h 125 -q 0.65 -dpi 72 -d ".$thumb_path." ".$svg_path_file);





                            $headers->add('Content-type','image/jpeg');
                            $headers->add('Cache-Control',"max-age=".(60*60*24));
                            $values=$headers->remove('Pragma');


                            //Yii::$app->response->headers=$values;


                            $content=file_get_contents($thumb_path_file);

                            Yii::$app->response->content=$content;

                            Yii::$app->response->send();


                        }

                    }




                }else{
                    //throw new \yii\web\HttpException(404, 'Not found', 404);
                }



            }else{

                //throw new \yii\web\HttpException(404, 'Not found', 404);
            }

        }else{

            //throw new \yii\web\HttpException(404, 'Not found', 404);
        }
    }


    public function actionEdit(){

        // [ 'Группа 1'=>['photo1', 'photo2', 'photo3'], 'Группа 2'=>['photo1', 'photo2', 'photo3'], 'Группа 3'=>['photo1', 'photo2', 'photo3'] ]

        if(Yii::$app->user->isGuest){



            $this->redirect(Url::toRoute(['photobooks/index']));

            return;
        }

        $this->layout='editor';
        $ref = Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');

        $selected_style_id=  Yii::$app->request->get('style_id', 0);


        if(!empty($ref) && !empty($id)){

            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);


            if($user_id!=Yii::$app->user->identity->getId()){

                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Страница не найдена'));

                $this->redirect(Url::toRoute(['photobooks/index']));

                return;

            }


            $model=new PhotobookForm();


            if(!$model->loadById($pb_id)){

                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Фотокнига не найдена'));

                $this->redirect(Url::toRoute(['photobooks/index']));

                return;
            }


            if(empty($model->data['pages'])){



                $this->redirect(Url::toRoute(['photobooks/upload-photos', 'ref'=>$ref, 'id'=>$id]));

                return;
            }


            $style_id=$model->style_id;


            $style=new StyleForm();

            if(!$style->loadById($style_id)){

                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Стиль для данной фотокниги не найден.'));

                $this->redirect(Url::toRoute(['photobooks/index']));

                return;
            }


            $selected_cover=new CoverForm();

            if(!$selected_cover->loadById($model->cover_id)){

                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Обложка для данной фотокниги не найден.'));

                $this->redirect(Url::toRoute(['photobooks/index']));

                return;
            }



            $covers=Cover::find()->where(['status'=>1])->all();


            $new_covers=[];
            foreach($covers as $key=>$cover){


                $new_covers[$cover->material_type][]=$cover;

            }


            $pages=$model->data['pages'];

            $base_price=(count($pages)-2)*$style->price_spread;

            $cover_price_sign=$selected_cover->price_sign;


            $selected_cover->price;

            $total_price=$base_price;

            if($cover_price_sign=="="){

                $total_price=$selected_cover->price;
            }else if($cover_price_sign=="+"){

                $total_price+=$selected_cover->price;
            }else if($cover_price_sign=="-"){

                $total_price-=$selected_cover->price;
            }


            $user=User::findOne(['id'=>$user_id]);



            $total_price=CurrencyConvertor::conv($total_price, $user->default_currency);


            $curse=CurrencyConvertor::getCurse($user->default_currency);


            $product_info=$model->getProductInfo();



            $total_price=$product_info['response']['price']*$curse;



            $settingForm=new SettingForm();


            $photobook_thumb_as_object= $settingForm->getValue('photobook_thumb_as_object', false);


            return $this->render('edit', ['model'=>$model, 'ref'=>$ref, 'id'=>$id, 'pb_id'=>$pb_id, 'user_id'=>$user_id, 'pages'=>$pages, 'style'=>$style, 'selected_cover'=>$selected_cover,
                'covers'=>$new_covers,  'total_price'=>$total_price, 'default_currency'=>$user->default_currency, 'cover_price_sign'=>$cover_price_sign,  'cover_price'=>$selected_cover->price,
                'price_spread'=>$style->price_spread, 'curse'=>$curse, 'photobook_thumb_as_object'=>$photobook_thumb_as_object]);

        }else{

            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Страница не найдена'));

            $this->redirect(Url::toRoute(['photobooks/index']));
        }

    }


    public function actionView(){


        $this->layout='editor';
        $view_access_key = Yii::$app->request->get('key');




        $ref = '';//Yii::$app->request->get('ref');
        $id= ''; //Yii::$app->request->get('id');


        if(!empty($view_access_key)){



            $photobookState=PhotobookState::findOne(['view_access_key'=>$view_access_key]);


            if(!$photobookState){

                //Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Страница не найдена'));

                $this->redirect(Url::toRoute(['photobooks/not-found']));
                return;
            }


            if($photobookState->status==PhotobookState::STATUS_CLOSE){


                if(Yii::$app->user->identity) {

                   // Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Ссылка закрыта'));

                    $this->redirect(Url::toRoute(['photobooks/access-close-not-found']));
                    return;

                }else if(Yii::$app->user->identity->id!=$photobookState->user_id){

                    //Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Ссылка закрыта'));

                    $this->redirect(Url::toRoute(['photobooks/access-close-not-found']));
                    return;

                }
            }






            $model=new PhotobookForm();


            if(!$model->loadById($photobookState->photobook_id)){

                //Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Страница не найдена'));

                $this->redirect(Url::toRoute(['photobooks/not-found']));

                return;
            }


            //Делаем замену полей на нужное нам состояние
            {

                $model->style_id=$photobookState->style_id;
                $model->cover_id=$photobookState->cover_id;

                $model->data=PhotobookForm::photosDecode($photobookState->data);
                $model->photos=PhotobookForm::photosDecode($photobookState->photos);

                $model->title_line_1=$photobookState->title_line_1;
                $model->title_line_2=$photobookState->title_line_2;
                $model->title_line_3=$photobookState->title_line_3;
                $model->title_line_4=$photobookState->title_line_4;




                $photobookState->comments=PhotobookForm::photosDecode( $photobookState->comments);



            }


            $user_id=$model->user_id;
            $pb_id=$model->id;

            $ref=AlphaId::id($user_id, false);
            $id=AlphaId::id($pb_id, false);


            $style_id=$model->style_id;


            $style=new StyleForm();

            if(!$style->loadById($style_id)){

                //Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Стиль для данной фотокниги не найден.'));

                $this->redirect(Url::toRoute(['photobooks/not-found']));

                return;
            }


            $selected_cover=new CoverForm();

            if(!$selected_cover->loadById($model->cover_id)){

                //Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Обложка для данной фотокниги не найден.'));

                $this->redirect(Url::toRoute(['photobooks/not-found']));

                return;
            }



            $covers=Cover::find()->where(['status'=>1])->all();


            $new_covers=[];
            foreach($covers as $key=>$cover){


                $new_covers[$cover->material_type][]=$cover;

            }


            $pages=$model->data['pages'];



            $base_price=(count($pages)-2)*$style->price_spread;

            $cover_price_sign=$selected_cover->price_sign;


            $selected_cover->price;

            $total_price=$base_price;

            if($cover_price_sign=="="){

                $total_price=$selected_cover->price;
            }else if($cover_price_sign=="+"){

                $total_price+=$selected_cover->price;
            }else if($cover_price_sign=="-"){

                $total_price-=$selected_cover->price;
            }


            return $this->render('view', ['model'=>$model, 'photobook_state'=>$photobookState, 'ref'=>$ref, 'id'=>$id, 'pb_id'=>$pb_id, 'user_id'=>$user_id, 'pages'=>$pages, 'style'=>$style, 'selected_cover'=>$selected_cover, 'covers'=>$new_covers, 'base_price'=>$base_price, 'total_price'=>$total_price]);

        }else{

            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Страница не найдена'));

            $this->redirect(Url::toRoute(['photobooks/not-found']));
        }

    }



    public function actionViewBook(){

        $this->layout='editor';
        $ref = Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');

        $selected_style_id=  Yii::$app->request->get('style_id', 0);


        if(!empty($ref) && !empty($id)){

            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);


            if($user_id!=Yii::$app->user->identity->getId()){

                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Страница не найдена'));

                $this->redirect(Url::toRoute(['photobooks/index']));

                return;

            }


            $model=new PhotobookForm();


            if(!$model->loadById($pb_id)){

                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Фотокнига не найдена'));

                $this->redirect(Url::toRoute(['photobooks/index']));

                return;
            }


            if(empty($model->data['pages'])){



                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'В фотокниги нет ни одного разворота'));

                $this->redirect(Url::toRoute(['photobooks/index', 'status'=>$model->status]));

                return;
            }



            $style_id=$model->style_id;


            $style=new StyleForm();

            if(!$style->loadById($style_id)){

                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Стиль для данной фотокниги не найден.'));

                $this->redirect(Url::toRoute(['photobooks/index']));

                return;
            }


            $selected_cover=new CoverForm();

            if(!$selected_cover->loadById($model->cover_id)){

                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Обложка для данной фотокниги не найден.'));

                $this->redirect(Url::toRoute(['photobooks/index']));

                return;
            }



            $covers=Cover::find()->where(['status'=>1])->all();


            $new_covers=[];
            foreach($covers as $key=>$cover){


                $new_covers[$cover->material_type][]=$cover;

            }


            $pages=$model->data['pages'];

            $base_price=(count($pages)-2)*$style->price_spread;

            $cover_price_sign=$selected_cover->price_sign;


            $selected_cover->price;

            $total_price=$base_price;

            if($cover_price_sign=="="){

                $total_price=$selected_cover->price;
            }else if($cover_price_sign=="+"){

                $total_price+=$selected_cover->price;
            }else if($cover_price_sign=="-"){

                $total_price-=$selected_cover->price;
            }


            $user=User::findOne(['id'=>$user_id]);



            $total_price=CurrencyConvertor::conv($total_price, $user->default_currency);


            $curse=CurrencyConvertor::getCurse($user->default_currency);



            return $this->render('viewBook', ['model'=>$model, 'ref'=>$ref, 'id'=>$id, 'pb_id'=>$pb_id, 'user_id'=>$user_id, 'pages'=>$pages, 'style'=>$style, 'selected_cover'=>$selected_cover,
                'covers'=>$new_covers,  'total_price'=>$total_price, 'default_currency'=>$user->default_currency, 'cover_price_sign'=>$cover_price_sign,  'cover_price'=>$selected_cover->price,
                'price_spread'=>$style->price_spread, 'curse'=>$curse]);

        }else{

            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Страница не найдена'));

            $this->redirect(Url::toRoute(['photobooks/index']));
        }


    }



    public function actionNotFound(){



        return $this->render('notfound', ['msg'=>Yii::t('app', 'Ой, страница была удалена или не существовала')]);


    }


    public function actionAccessCloseNotFound(){

        return $this->render('notfound', ['msg'=>Yii::t('app', 'Доступ к странице закрыт. Ожидайте ссылку с учетом Ваших комментариев')]);


    }




}
