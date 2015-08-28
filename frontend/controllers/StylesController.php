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
use common\models\Style;
use common\models\StyleForm;
use common\models\Font;
use yii\helpers\ArrayHelper;
/**
 * Site controller
 */
class StylesController extends BaseController
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


        $conditions=['delete'=>0];

        if($status>=0){

            $conditions['status']=$status;
        }

        $filters_attr=[
            0=>['params'=>[],
                'label'=>Yii::t('app', 'Все'),
                'count'=>0,
                'active'=>($status<0)
            ],
            1=>['params'=>['status'=>Style::STATUS_PUBLISHED],
                'label'=>Yii::t('app', 'Опубликованные'),
                'count'=>0,
                'active'=>$status==Style::STATUS_PUBLISHED
            ],
            2=>[
                'params'=>['status'=>Style::STATUS_UNPUBLISHED],
                'label'=>Yii::t('app', 'Не опубликованные'),
                'count'=>0,
                'active'=>$status==Style::STATUS_UNPUBLISHED

            ],
        ];

        $query=null;




        if(!empty($conditions)){
            $query = Style::find()->where($conditions);
        }else{
            $query = Style::find();
        }

        $countQuery = clone $query;

        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $styles = $query->orderBy([ 'weight'=>SORT_DESC])->offset($pages->offset)
            ->limit($pages->limit)
            ->all();


        return $this->render('index', ['pages'=>$pages, 'styles'=>$styles, 'filters_attr'=>$filters_attr]);
    }

    private function _generateRandomLayout($num=1){

        $data=['label'=>Yii::t('app', 'Группа макетов {num}', ['num'=>$num]), 'background_color'=>'#FFFFFF', 'background_image'=>null, 'template_ids'=>[]];

        $templates=Template::find()->orderBy([ 'count_placeholder'=>SORT_DESC])->all();

        if(!empty($templates)){

            Yii::getLogger()->log('tmple:'.print_r($templates, true), YII_DEBUG);

            $max_count_placeholder=$templates[0]->count_placeholder;

            for($i=1; $i<=$max_count_placeholder; $i++){


                $templates=Template::find()->where(['count_placeholder'=>$i])->all();

                $index=rand(0, count($templates)-1);

                $data['template_ids']['ph_count_'.$i]= $templates[$index]->id;

            }

            return $data;

        }else{

            return $data;
        }

    }

    public function actionDelete(){

        $this->layout='default';

        $id = Yii::$app->request->get('id', 0);

        if(!empty($id)){

            $model=new StyleForm();
            if($model->loadById($id)){

                if($model->delete()){

                    Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Стиль успешно удален.'));

                    $this->redirect(Url::toRoute(['styles/index']));
                }else{

                    Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Не удалось удалить.'));

                    $this->redirect(Url::toRoute(['styles/index']));

                }
            }


        }else{


            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Не удалось удалить.'));

            $this->redirect(Url::toRoute(['styles/index']));

        }


    }



    public function actionAdd(){

        $this->layout='default';


        $count=Style::find()->count();

        $count++;

        $model=new Style();

       // $model->count_placeholder=0;


        $model->name=Yii::t('app', 'Новый стиль {num}', ['num'=>$count]);
        $model->weight=$count;
        $model->thumb_key="default_style_thumb";
        $model->status=Style::STATUS_PUBLISHED;

        $layout=$this->_generateRandomLayout(1);

        $data=[];
        $data['layouts']=[$layout];



        $model->data=json_encode($data);


        if($model->save()){

            $this->redirect(Url::toRoute(['styles/edit', 'id'=> $model->id]));

        }else{

            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Не удалось сохранить стиль.'));

            $this->redirect(Url::toRoute(['styles/index']));
        }

        return;
    }



    public function actionEdit(){


        $this->layout='default';

        $id = Yii::$app->request->get('id', -1);

        $style=Style::findOne(['id'=>$id]);

        if(!$style){

            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Стиль не найден'));

            $this->redirect(Url::toRoute(['styles/index']));
        }

        $fonts=Font::find()->all();

        $fonts= ArrayHelper::map($fonts, 'id', 'name');

        $style->data=json_decode($style->data, true);

        return $this->render('edit', ['style'=>$style, 'fonts'=>$fonts]);
    }


    public function actionViewSvg(){


        header('Content-type: image/svg+xml');

        $this->layout='empty';

        $id = Yii::$app->request->get('id', -1);

        $template=Template::findOne(['id'=>$id]);



        if(!$template){

            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Макет не найден'));

            $this->redirect(Url::toRoute(['templates/index']));
        }

        echo $template->svg;

        //return $this->render('edit', ['template'=>$template]);
    }





}
