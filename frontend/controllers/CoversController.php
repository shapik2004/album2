<?php
namespace frontend\controllers;

use app\components\AlphaId;
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
class CoversController extends BaseController
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

    public function actionIndex()
    {
        $this->layout='default';

        $status = Yii::$app->request->get('status', -1);


        //$conditions=['status >='=>0];

        if($status>=0){

            $conditions['status']=$status;
        }

        $filters_attr=[
            0=>['params'=>[],
                'label'=>Yii::t('app', 'Все'),
                'count'=>0,
                'active'=>($status<0)
            ],
            1=>['params'=>['status'=>Cover::STATUS_PUBLISHED],
                'label'=>Yii::t('app', 'Опубликованные'),
                'count'=>0,
                'active'=>$status==Cover::STATUS_PUBLISHED
            ],
            2=>[
                'params'=>['status'=>Cover::STATUS_UNPUBLISHED],
                'label'=>Yii::t('app', 'Не опубликованные'),
                'count'=>0,
                'active'=>$status==Cover::STATUS_UNPUBLISHED

            ],
        ];

        $query=null;




        if(!empty($conditions)){
            $query = Cover::find()->where($conditions);
        }else{
            $query = Cover::find();
        }

        $countQuery = clone $query;

        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $covers = $query->orderBy([ 'created_at'=>SORT_DESC])->offset($pages->offset)
            ->limit($pages->limit)
            ->all();


        return $this->render('index', ['pages'=>$pages, 'covers'=>$covers, 'filters_attr'=>$filters_attr]);
    }



    public function actionDelete(){

        $this->layout='default';

        $id = Yii::$app->request->get('id', 0);

        if(!empty($id)){

            $model=new CoverForm();
            if($model->loadById($id)){

                if($model->delete()){

                    Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Обложка успешно удалена.'));

                    $this->redirect(Url::toRoute(['covers/index']));
                }else{

                    Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Не удалось удалить.'));

                    $this->redirect(Url::toRoute(['covers/index']));

                }
            }


        }else{


            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Не удалось удалить.'));

            $this->redirect(Url::toRoute(['covers/index']));

        }


    }



    public function actionAdd(){

        $this->layout='default';


        $count=Cover::find()->count();

        $count++;

        $model=new CoverForm();

       // $model->count_placeholder=0;


        $model->name=Yii::t('app', 'Новая обложка {num}', ['num'=>$count]);


        $model->status=Cover::STATUS_UNPUBLISHED;




        if($model->save()){

            $this->redirect(Url::toRoute(['covers/edit', 'id'=> $model->id]));

        }else{

            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Не удалось сохранить обложку.'));

            $this->redirect(Url::toRoute(['covers/index']));
        }

        return;
    }



    public function actionEdit(){


        $this->layout='default';

        $id = Yii::$app->request->get('id', -1);

        $cover=Cover::findOne(['id'=>$id]);

        if(!$cover){

            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Обложка не найдена'));

            $this->redirect(Url::toRoute(['covers/index']));
        }


        $material_types=[

            'кожа'=>'кожа',
            'лен'=>'лен'

        ];

        $price_signs=[

            '='=>Yii::t('app', 'Заменить базовую цену'),
            '+'=>Yii::t('app', 'Добавить к базовой цене'),
            '-'=>Yii::t('app', 'Отнять от базовой цены')

        ];



        return $this->render('edit', ['cover'=>$cover, 'material_types'=>$material_types, 'price_signs'=>$price_signs]);
    }






}
