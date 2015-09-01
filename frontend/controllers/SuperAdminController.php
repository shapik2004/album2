<?php
namespace frontend\controllers;

use app\components\AlphaId;
use app\components\DemoAccountIdValidator;
use app\components\UserUrl;
use common\models\SettingForm;
use common\models\UserSetting;
use common\models\AdminUserEditForm;
use Yii;

use yii\filters\AccessControl;

use yii\base\DynamicModel;

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
class SuperAdminController extends BaseController
{

    private  $_dynamicModel =null;
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
                        'actions' => ['settings'],
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

    public function beforeAction($action)
    {

        //$msg='TEST2:'.$action;
        //Yii::getLogger()->log('TEST2'.print_r($action, true), YII_DEBUG);
        if ($action->id == 'settings') {

           // Yii::getLogger()->log('TEST3', YII_DEBUG);
            $this->enableCsrfValidation = false;
        }



        return  parent::beforeAction($action);
    }

    public function actionSettings()
    {




        if(\Yii::$app->user->isGuest){

            return $this->redirect(Url::toRoute('/'));

        }

        if(\Yii::$app->user->identity && \Yii::$app->user->identity->role!=User::ROLE_ADMIN){

            return $this->redirect(Url::toRoute('/'));

        }



        $page = Yii::$app->request->get('page', 'general');



        $_dynamicModel=$this->_dynamicModel;


        $sidemenus=[

            'general'=>[

                'title'=>Yii::t('app', 'Общие настройки'),
                'url'=>Url::toRoute(['super-admin/settings', 'page'=>'general']),
                'active'=>($page=='general'),
                'settings'=>[
                    'site_name',
                    'demo_account_id',
                    'manager_notification_email',
                    'manager_notification_status-'.Photobook::STATUS_NEW,
                    'manager_notification_status-'.Photobook::STATUS_SENT_TO_CUSTOMER,
                    'manager_notification_status-'.Photobook::STATUS_WAIT_EDIT_FROM_CUSTOMER,
                    'manager_notification_status-'.Photobook::STATUS_SENT_TO_PRINT,
                    'manager_notification_status-'.Photobook::STATUS_READY_FOR_PRINT_WAIT_PAYMENT,
                    'manager_notification_status-'.Photobook::STATUS_READY_FOR_PRINT_PAID,
                    'manager_notification_status-'.Photobook::STATUS_READY_SENT_TO_PRINT,
                    'manager_notification_status-'.Photobook::STATUS_READY,
                    'manager_notification_status-'.Photobook::STATUS_READY_SENT_TO_CLIENT,
                    'manager_notification_status-'.Photobook::STATUS_RECEIVED_FEEDBACK,
                    'manager_notification_status-'.Photobook::STATUS_ARCHIVE,
                    'note_upload_page',
                    'photobook_thumb_as_object'

                ],
                'validation'=>[
                    'site_name'=>[
                        ['value', 'required'],
                        ['value', 'string', 'min' => 2, 'max' => 255],
                    ],
                    'manager_notification_email'=>[
                        ['value', 'filter', 'filter' => 'trim'],
                        ['value', 'email'],
                    ],
                    'demo_account_id'=>[
                        ['value', 'filter', 'filter' => 'trim'],
                        ['value', 'app\components\DemoAccountIdValidator'],
                    ]


                ]
            ],
            'currency'=>[

                'title'=>Yii::t('app', 'Курсы валют'),
                'url'=>Url::toRoute(['super-admin/settings', 'page'=>'currency']),
                'active'=>($page=='currency'),
                'settings'=>[
                    'currencies',
                    'main_currency',
                    'default_currency'
                ]

            ],
            'email_notification'=>[

                'title'=>Yii::t('app', 'Шаблоны Email оповещений'),
                'url'=>Url::toRoute(['super-admin/settings', 'page'=>'email_notification']),
                'active'=>($page=='email_notification'),
                'settings'=>[
                    'manager_notification_change_status',
                    'manager_notification_new_user',
                    'user_notification_change_status',
                    'user_notification_invoice_link',
                    'user_notification_payment_received',
                    'customer_notification_link_for_comments',
                ]

            ],
            'liqpay'=>[

                'title'=>Yii::t('app', 'Настройки LiqPay'),
                'url'=>Url::toRoute(['super-admin/settings', 'page'=>'liqpay']),
                'active'=>($page=='liqpay'),
                'settings'=>[
                    'liqpay_public_key',
                    'liqpay_private_key',

                ]

            ]
        ];

        $active_page=$sidemenus[$page];




       // Yii::$app->request->v

        Yii::getLogger()->log('TEST1', YII_DEBUG);

        $form= Yii::$app->request->post('SettingForm', null);

        $model=new SettingForm();

        $errors_list_data=[];

        if(!empty($form)){

            foreach($form as $name=>$value){


                if(!empty($active_page['validation']) && !empty($active_page['validation'][$name])) {

                    $this->_dynamicModel = DynamicModel::validateData(compact('value'), $active_page['validation'][$name]);


                    if($this->_dynamicModel->hasErrors()){


                        $active_page['errors'][$name]=$this->_dynamicModel->errors['value'];

                        $errors_list_data[$name]=$value;
                    }else{




                        $model->setValue($name, $value);
                    }

                }else{


                    $model->setValue($name, $value);
                }



            }

        }





        $settings=[];




        foreach($active_page['settings'] as $key=>$setting){

            if(!isset($errors_list_data[$setting])) {
                $settings[$setting] = $model->getValue($setting, '');
            }else{

                $settings[$setting] = $errors_list_data[$setting];

            }
        }


        $this->layout='default';
        return $this->render($page,['sidemenus'=>$sidemenus, 'settings'=>$settings, 'active_page'=>$active_page]);

    }


    public static function validateDemoAccountId($attribute, $params){


        //if (!in_array($attribute, ['USA', 'Web'])) {
        //    $attribute->addError($attribute, 'The country must be either "USA" or "Web".');
        //}

    }

    public function actionUserEdit(){

        $this->layout='default';

        if(Yii::$app->user->isGuest){

            $this->redirect(Url::toRoute(['user/logout']));
            return;

        }

        if(Yii::$app->user->identity->role!=User::ROLE_ADMIN){

            $this->redirect(Url::toRoute(['user/logout']));
            return;

        }


        $user_id = Yii::$app->request->get('id', null);


        if(empty($user_id)){



            $this->redirect(Url::toRoute(['super-admin/users']));
            return;
        }



        $model=new AdminUserEditForm();

        if($model->load(Yii::$app->request->post(), 'AdminUserEditForm')){

            if($model->validate()){


                $model->id=$user_id;

                if($model->save()){

                    Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Успешно сохранено'));

                    $this->redirect(Url::toRoute(['super-admin/users', 'role'=>$model->role]));
                    return;

                }else{

                    Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Ошибка сохранения'));

                    $this->redirect(Url::toRoute(['super-admin/users', 'role'=>$model->role]));
                    return;
                }
            }
        }


        if(!$model->loadByUserId($user_id)){


            $this->redirect(Url::toRoute(['super-admin/users']));
            return;

        }


        $settingForm=new SettingForm();


        $currencies=$settingForm->getValue('currencies', []);



        $currencies_new=[];



        foreach($currencies as $key=>$currency){


            $currencies_new[$currency['code']]=$currency['code'];

        }

        $currencies=$currencies_new;


        $roles=[
            User::ROLE_USER=>Yii::t('app', 'Пользователь'),
            User::ROLE_ADMIN=>Yii::t('app', 'Супер администратор'),

        ];


        return $this->render('userEdit',['model'=>$model, 'currencies'=>$currencies, 'roles'=>$roles]);


    }

    public function actionPhotobooks(){

        $this->layout='default';

        if(Yii::$app->user->isGuest){

            $this->redirect(Url::toRoute(['user/logout']));
            return;

        }

        if(Yii::$app->user->identity->role!=User::ROLE_ADMIN){

            $this->redirect(Url::toRoute(['user/logout']));
            return;

        }




        $status = Yii::$app->request->get('status', Photobook::STATUS_NEW);

        /*if($status==Photobook::STATUS_DEMO){

            $this->redirect(Url::toRoute(['photobooks/index']));
            return;
        }*/


       // $user_id=Yii::$app->user->identity->getId();


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

        $query = Photobook::find()->where([ 'status' => $status]);
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
                'count'=>Photobook::find()->where([ 'status'=>Photobook::STATUS_NEW])->count()
            ],
            [
                'title'=>Yii::t('app', 'Отправленные на согласование с клиентом'),
                'status'=>Photobook::STATUS_SENT_TO_CUSTOMER,
                'count'=>Photobook::find()->where([ 'status'=>Photobook::STATUS_SENT_TO_CUSTOMER])->count()
            ],
            [
                'title'=>Yii::t('app', 'Ожидающие правок'),
                'status'=>Photobook::STATUS_WAIT_EDIT_FROM_CUSTOMER,
                'count'=>Photobook::find()->where([ 'status'=>Photobook::STATUS_WAIT_EDIT_FROM_CUSTOMER])->count()
            ],
            [
                'title'=>Yii::t('app', 'Утвержденные клиентом'),
                'status'=>Photobook::STATUS_SENT_TO_PRINT,
                'count'=>Photobook::find()->where(['status'=>Photobook::STATUS_SENT_TO_PRINT])->count()
            ],
            [
                'title'=>Yii::t('app', 'Готовы к печати, ожидает оплаты'),
                'status'=>Photobook::STATUS_READY_FOR_PRINT_WAIT_PAYMENT,
                'count'=>Photobook::find()->where([ 'status'=>Photobook::STATUS_READY_FOR_PRINT_WAIT_PAYMENT])->count()
            ],
            [
                'title'=>Yii::t('app', 'Готовы к печати, оплачено'),
                'status'=>Photobook::STATUS_READY_FOR_PRINT_PAID,
                'count'=>Photobook::find()->where([ 'status'=>Photobook::STATUS_READY_FOR_PRINT_PAID])->count()
            ],
            [
                'title'=>Yii::t('app', 'В производстве'),
                'status'=>Photobook::STATUS_READY_SENT_TO_PRINT,
                'count'=>Photobook::find()->where([ 'status'=>Photobook::STATUS_READY_SENT_TO_PRINT])->count()
            ],
            [
                'title'=>Yii::t('app', 'Готовые'),
                'status'=>Photobook::STATUS_READY,
                'count'=>Photobook::find()->where(['status'=>Photobook::STATUS_READY])->count()
            ],
            [
                'title'=>Yii::t('app', 'Отправленные'),
                'status'=>Photobook::STATUS_READY_SENT_TO_CLIENT,
                'count'=>Photobook::find()->where([ 'status'=>Photobook::STATUS_READY_SENT_TO_CLIENT])->count()
            ],
            [
                'title'=>Yii::t('app', 'Полученные'),
                'status'=>Photobook::STATUS_RECEIVED_FEEDBACK,
                'count'=>Photobook::find()->where([ 'status'=>Photobook::STATUS_RECEIVED_FEEDBACK])->count()
            ],
            [
                'title'=>Yii::t('app', 'Архив'),
                'status'=>Photobook::STATUS_ARCHIVE,
                'count'=>Photobook::find()->where(['status'=>Photobook::STATUS_ARCHIVE])->count()
            ]
        ];



        $settingForm=new SettingForm();


        $photobook_thumb_as_object= $settingForm->getValue('photobook_thumb_as_object', false);




        return $this->render('photobooks', ['pages'=>$pages, 'orders'=>$orders, 'status'=>$status, 'sidemenus'=>$sidemenus, 'photobook_thumb_as_object'=>$photobook_thumb_as_object]);


    }


    public function actionUsers(){


        $this->layout='default';

        if(Yii::$app->user->isGuest){

            $this->redirect(Url::toRoute(['user/logout']));
            return;

        }

        if(Yii::$app->user->identity->role!=User::ROLE_ADMIN){

            $this->redirect(Url::toRoute(['user/logout']));
            return;

        }


        $role = Yii::$app->request->get('role', User::ROLE_USER);


        $username = Yii::$app->request->get('username', null);

        $email = Yii::$app->request->get('email', null);

        $user_id = Yii::$app->request->get('user_id', null);

        $filter=[
            'username'=>$username,
            'email'=>$email,
            'user_id'=>$user_id

        ];




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


        $condition=[ 'role' => $role, 'status'=>User::STATUS_ACTIVE];



        if(!empty($email)){


            $condition['email']=$email;

        }


        if(!empty($user_id)){


            $condition['id']=$user_id;

        }


        $query = User::find()->where($condition);

        if(!empty($username)){


            $query=$query->andFilterWhere(['like', 'username', $username]);


        }


        $countQuery = clone $query;

        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $users = $query->orderBy([ 'created_at'=>SORT_DESC])->offset($pages->offset)
            ->limit($pages->limit)
            ->all();



        $user_ids=[];

        foreach($users as $key=>$user){


            $user_ids[]=$user->id;

        }

        //echo count($user_ids);

        $users_settings=UserSetting::find()->where(['user_id'=>$user_ids])->all();


        //echo count($users_settings);

        $mapUsersSettings=[];



        foreach($users_settings as $key=>$users_setting){


            $mapUsersSettings[$users_setting->user_id]=$users_setting;

        }


        $sidemenus=[
            [
                'title'=>Yii::t('app', 'Пользователи'),
                'role'=>User::ROLE_USER,
                'count'=>User::find()->where(['role'=>User::ROLE_USER, 'status'=>User::STATUS_ACTIVE])->count()
            ],
            [
                'title'=>Yii::t('app', 'Супер администраторы'),
                'role'=>User::ROLE_ADMIN,
                'count'=>User::find()->where(['role'=>User::ROLE_ADMIN, 'status'=>User::STATUS_ACTIVE])->count()
            ]

        ];


        return $this->render('users',['users'=>$users, 'pages'=>$pages, 'users_settings'=>$mapUsersSettings, 'sidemenus'=>$sidemenus, 'role'=>$role, 'filter'=>$filter]);


    }







}
