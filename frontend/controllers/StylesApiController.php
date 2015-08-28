<?php
namespace frontend\controllers;

use app\components\AlphaId;
use common\models\StyleForm;
use Yii;

use yii\filters\AccessControl;

use common\models\Photobook;
use common\models\PhotobookForm;
use yii\web\UploadedFile;
use app\components\UserUrl;
use common\models\Style;


use yii\helpers\Url;
/**
 * Site controller
 */
class StylesApiController extends BaseController
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

    public function actionAddGroup(){

        $result=[];
        $this->layout='json';

        $id= Yii::$app->request->get('id');
        $after_group= Yii::$app->request->get('after_group', 0);
        //$group_name=Yii::$app->request->get('group');

        if(!empty($id)){



            $model=new StyleForm();
            if($model->loadById($id)){



                $group_index=0;

                $num=0;
                while(isset($model->data['layouts'][$group_index])){
                    $num++;
                    $group_index=$num;
                }



                //Yii::getLogger()->log('Max:'.print_r($model->photos, true), YII_DEBUG);
                $result=$model->addGroup(Yii::t('app', 'Группа макетов {num}', ['num'=>$group_index]), $after_group);
            }else{
                $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
            }

        }else{

            $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }

        return $this->render('json', ['result'=>$result]);


    }

    public function actionChangeGroupName(){

        $result=[];
        $this->layout='json';

        $id= Yii::$app->request->get('id');
        $group_index=Yii::$app->request->get('group_index');
        $group_name=Yii::$app->request->get('newgroup');

        if( !empty($id)){

            if(!empty($group_name)){


                $model=new StyleForm();
                if($model->loadById($id)){

                   // Yii::getLogger()->log('Max:'.print_r($model->photos, true), YII_DEBUG);
                    $result=$model->changeGroupName($group_index, $group_name);
                }else{
                    $result=['error'=>['msg'=>Yii::t('app', 'Стиль не найден')]];
                }
            }else{
                $result=['error'=>['msg'=>Yii::t('app', 'Не верно заданные параметры')]];
            }
        }else{

            $result=['error'=>['msg'=>Yii::t('app', 'Стиль не найден')]];
        }

        return $this->render('json', ['result'=>$result]);

    }

    public function actionSetTemplateId(){


        $result=[];
        $this->layout='json';
        $id= Yii::$app->request->get('id');
        $group_index=Yii::$app->request->get('group_index', 0);
        $ph_count=Yii::$app->request->get('ph_count', 0);
        $template_id=Yii::$app->request->get('template_id', 0);

        if(!empty($id)){


            $model=new StyleForm();
            if($model->loadById($id)){

                // Yii::getLogger()->log('Max:'.print_r($model->photos, true), YII_DEBUG);
                $result=$model->setTemplateId($group_index, $ph_count, $template_id);
            }else{
                $result=['error'=>['msg'=>Yii::t('app', 'Стиль не найден')]];
            }

        }else{

            $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }

        return $this->render('json', ['result'=>$result]);
    }

    public function actionDelete(){

        $result=[];
        $this->layout='json';
        $id= Yii::$app->request->get('id');
        $new_name=Yii::$app->request->get('name');

        if(!empty($id)){


                $user_id=Yii::$app->user->identity->getId();


                $model=new PhotobookForm();
                if($model->loadById($id)){

                    $result=$model->delete();

                    //$result=$model->changeName($new_name);

                }else{
                    $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
                }

        }else{

            $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }

        return $this->render('json', ['result'=>$result]);

    }


    public function actionDeleteGroup(){

        $result=[];
        $this->layout='json';

        $id= Yii::$app->request->get('id');
        $group_index=Yii::$app->request->get('group_index');

        if(!empty($id)){

                $model=new StyleForm();
                if($model->loadById($id)){

                    $result=$model->deleteGroup($group_index);

                }else{
                    $result=['error'=>['msg'=>Yii::t('app', 'Стиль не найден')]];
                }

        }else{

            $result=['error'=>['msg'=>Yii::t('app', 'Стиль не найден')]];
        }

        return $this->render('json', ['result'=>$result]);

    }


    public function actionChangeGroupBackgroundColor(){

        $result=[];
        $this->layout='json';

        $id= Yii::$app->request->get('id');
        $group_index=Yii::$app->request->get('group_index');
        $color=Yii::$app->request->get('color', 'FFFFFF');

        $color='#'.$color;
        if(!empty($id)){


               // $user_id=Yii::$app->user->identity->getId();


                $model=new StyleForm();
                if($model->loadById($id)){


                    $result=$model->changeGroupBackgroundColor($group_index, $color);

                }else{
                    $result=['error'=>['msg'=>Yii::t('app', 'Стиль не найден')]];
                }

        }else{

            $result=['error'=>['msg'=>Yii::t('app', 'Стиль не найден')]];
        }

        return $this->render('json', ['result'=>$result]);

    }

    public function actionChangeName(){

        $result=[];
        $this->layout='json';

        $id= Yii::$app->request->get('id');

        $new_name=Yii::$app->request->get('name');

        if(!empty($id)){

            if(!empty($new_name)){



                $model=new StyleForm();
                if($model->loadById($id)){


                    $result=$model->changeName($new_name);

                }else{
                    $result=['error'=>['msg'=>Yii::t('app', 'Стиль не найден')]];
                }
            }else{
                $result=['error'=>['msg'=>Yii::t('app', 'Не верно заданные параметры')]];
            }
        }else{

            $result=['error'=>['msg'=>Yii::t('app', 'Стиль не найден')]];
        }

        return $this->render('json', ['result'=>$result]);

    }

    public function actionUpdate(){

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id= Yii::$app->request->get('id', null);
        $value= Yii::$app->request->post('value', '');

        $field_name= Yii::$app->request->get('field_name');

        if(empty($id)){
            return ['error'=>['msg'=>Yii::t('app', 'Стиль не найден')]];
        }

        if(empty($field_name)){

            return ['error'=>['msg'=>Yii::t('app', 'field_name не задан')]];
        }


        $model=new StyleForm();
        if(!$model->loadById($id)){

            return ['error'=>['msg'=>Yii::t('app', 'Стиль не найден')]];
        }


        return $model->updateField($field_name, $value);

    }

    public function actionUploadPaddedPassepartout(){


        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $id= Yii::$app->request->get('id');

        if(!empty($id)){

            $model=new StyleForm();

            if($model->loadById($id)){

                return $model->updatePaddedPassepartout();

            }else{

                return ['error'=>['msg'=>Yii::t('app', 'Стиль не найден')]];

            }


        }else{

            return ['error'=>['msg'=>Yii::t('app', 'Стиль не найден')]];
        }

    }


    public function actionUploadPaddedCover(){


        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $id= Yii::$app->request->get('id');

        if(!empty($id)){

            $model=new StyleForm();

            if($model->loadById($id)){

                return $model->updatePaddedCover();

            }else{

                return ['error'=>['msg'=>Yii::t('app', 'Стиль не найден')]];

            }


        }else{

            return ['error'=>['msg'=>Yii::t('app', 'Стиль не найден')]];
        }
    }



    public function actionUploadCoverFront(){


        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $id= Yii::$app->request->get('id');

        if(!empty($id)){

            $model=new StyleForm();

            if($model->loadById($id)){

                return $model->updateCoverFront();

            }else{

                return ['error'=>['msg'=>Yii::t('app', 'Стиль не найден')]];

            }


        }else{

            return ['error'=>['msg'=>Yii::t('app', 'Стиль не найден')]];
        }
    }


    public function actionUploadCoverBack(){


        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $id= Yii::$app->request->get('id');

        if(!empty($id)){

            $model=new StyleForm();

            if($model->loadById($id)){

                return $model->updateCoverBack();

            }else{

                return ['error'=>['msg'=>Yii::t('app', 'Стиль не найден')]];

            }


        }else{

            return ['error'=>['msg'=>Yii::t('app', 'Стиль не найден')]];
        }
    }



    public function actionUploadThumb()
    {
        $this->layout='json';

        $id= Yii::$app->request->get('id');


        if(!empty($id)){

            Yii::getLogger()->log('post:'.print_r(Yii::$app->request->post(), true), YII_DEBUG);

            $model=new StyleForm();

            if($model->loadById($id)){

                $result=$model->updateThumbImage();

            }else{
                $result=['error'=>['msg'=>Yii::t('app', 'Стиль не найден')]];
            }

        }else{

            $result=['error'=>['msg'=>Yii::t('app', 'Стиль не найден')]];
        }

        return $this->render('json', ['result'=>$result]);
    }

    public function actionUploadBackground()
    {
        $this->layout='json';

        $id= Yii::$app->request->get('id');
        $group_index= Yii::$app->request->get('group_index');

        if(!empty($id)){

            Yii::getLogger()->log('post:'.print_r(Yii::$app->request->post(), true), YII_DEBUG);

            $model=new StyleForm();

            if($model->loadById($id)){

                $result=$model->updateBackgroundImage($group_index);

            }else{
                $result=['error'=>['msg'=>Yii::t('app', 'Стиль не найден')]];
            }



        }else{

            $result=['error'=>['msg'=>Yii::t('app', 'Стиль не найден')]];
        }

        return $this->render('json', ['result'=>$result]);
    }





}
