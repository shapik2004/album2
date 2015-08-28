<?php
namespace frontend\controllers;

use app\components\AlphaId;
use common\models\CoverForm;
use Yii;

use yii\filters\AccessControl;

use common\models\Photobook;
use common\models\PhotobookForm;
use yii\web\UploadedFile;
use app\components\UserUrl;
use common\models\Cover;


use yii\helpers\Url;
/**
 * Site controller
 */
class CoversApiController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            /*'access' => [
                'class' => AccessControl::className(),
                'only' => ['upload', 'change-group-name', 'add-group'],
                'rules' => [
                    [
                        'actions' => ['upload', 'change-group-name', 'add-group'],
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




    public function actionUpdate(){

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id= Yii::$app->request->get('id', null);
        $value= Yii::$app->request->post('value', '');

        $field_name= Yii::$app->request->get('field_name');

        if(empty($id)){
            return ['error'=>['msg'=>Yii::t('app', 'Обложка не найдена')]];
        }

        if(empty($field_name)){

            return ['error'=>['msg'=>Yii::t('app', 'field_name не задан')]];
        }


        $model=new CoverForm();
        if(!$model->loadById($id)){

            return ['error'=>['msg'=>Yii::t('app', 'Обложка не найдена')]];
        }


        return $model->updateField($field_name, $value);

    }



    public function actionUploadPaddedCover(){


        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $id= Yii::$app->request->get('id');

        if(!empty($id)){

            $model=new CoverForm();

            if($model->loadById($id)){

                return $model->updatePaddedCover();

            }else{

                return ['error'=>['msg'=>Yii::t('app', 'Обложка не найдена')]];

            }


        }else{

            return ['error'=>['msg'=>Yii::t('app', 'Обложка не найдена')]];
        }
    }



    public function actionUploadCoverFront(){


        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $id= Yii::$app->request->get('id');

        if(!empty($id)){

            $model=new CoverForm();

            if($model->loadById($id)){

                return $model->updateCoverFront();

            }else{

                return ['error'=>['msg'=>Yii::t('app', 'Обложка не найдена')]];

            }


        }else{

            return ['error'=>['msg'=>Yii::t('app', 'Обложка не найдена')]];
        }
    }


    public function actionUploadCoverBack(){


        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $id= Yii::$app->request->get('id');

        if(!empty($id)){

            $model=new CoverForm();

            if($model->loadById($id)){

                return $model->updateCoverBack();

            }else{

                return ['error'=>['msg'=>Yii::t('app', 'Обложка не найдена')]];

            }


        }else{

            return ['error'=>['msg'=>Yii::t('app', 'Обложка не найдена')]];
        }
    }



    public function actionUploadThumb()
    {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id= Yii::$app->request->get('id');


        if(!empty($id)){

            Yii::getLogger()->log('post:'.print_r(Yii::$app->request->post(), true), YII_DEBUG);

            $model=new CoverForm();

            if($model->loadById($id)){

                $result=$model->updateCoverThumb();

            }else{
                $result=['error'=>['msg'=>Yii::t('app', 'Обложка не найдена')]];
            }

        }else{

            $result=['error'=>['msg'=>Yii::t('app', 'Обложка не найдена')]];
        }

        return $result;
    }

    public function actionUploadBackground()
    {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $id= Yii::$app->request->get('id');
        $group_index= Yii::$app->request->get('group_index');

        if(!empty($id)){

            Yii::getLogger()->log('post:'.print_r(Yii::$app->request->post(), true), YII_DEBUG);

            $model=new CoverForm();

            if($model->loadById($id)){

                $result=$model->updateBackgroundImage($group_index);

            }else{
                $result=['error'=>['msg'=>Yii::t('app', 'Обложка не найдена')]];
            }



        }else{

            $result=['error'=>['msg'=>Yii::t('app', 'Обложка не найдена')]];
        }

        return $result;
    }





}
