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

use common\models\Template;
/**
 * Site controller
 */
class TemplatesController extends BaseController
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


        $ph = Yii::$app->request->get('ph', 0);
        $text_object = Yii::$app->request->get('text_object', -1);
        $publish = Yii::$app->request->get('publish', -1);


        $user_id=Yii::$app->user->identity->getId();


        $conditions=[];

        if($ph>0){

            $conditions['count_placeholder']=$ph;
        }

        if($publish>=0){

            $conditions['publish']=$publish;
        }

        if($text_object>0){

            $conditions['text_object']=($text_object==1);
        }

        $filters_attr=[
            0=>['params'=>[],
                'label'=>Yii::t('app', 'Все'),
                'count'=>0,
                'active'=>($text_object<0 &&  $publish<0 && empty($attr['params']['publish']) && empty($attr['params']['text_object']))
            ],
            1=>['params'=>['publish'=>1],
                'label'=>Yii::t('app', 'Опубликованные'),
                'count'=>0,
                'active'=>$publish==1
            ],
            2=>['params'=>['publish'=>0],
                'label'=>Yii::t('app', 'Не опубликованные'),
                'count'=>0,
                'active'=>$publish==0
            ]

        ];


        $filters_ph=[];

        $filters_ph[]=['ph'=>0, 'label'=>Yii::t('app', 'Все', ['ph'=>0])];

        for($i=1; $i<=8; $i++){

            $filters_ph[]=['ph'=>$i, 'label'=>Yii::t('app', '{ph} фото', ['ph'=>$i])];
        }

        $query=null;




        if(!empty($conditions)){
            $query = Template::find()->where($conditions);
        }else{
            $query = Template::find();
        }

        $countQuery = clone $query;

        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $templates = $query->orderBy([ 'weight'=>SORT_DESC])->offset($pages->offset)
            ->limit($pages->limit)
            ->all();


        return $this->render('index', ['pages'=>$pages, 'templates'=>$templates, 'filters_attr'=>$filters_attr, 'filters_ph'=>$filters_ph, 'publish'=>$publish, 'text_object'=>$text_object, 'ph'=>$ph]);
    }

    public function actionAdd(){

        $this->layout='default';


        $count=Template::find()->count();

        $count++;

        $model=new Template();

        $model->count_placeholder=0;


        $model->weight=$count;


        if($model->save()){


            $model->name='#'. $model->id;

            if($model->update(false)){

                $this->redirect(Url::toRoute(['templates/edit', 'id' => $model->id]));

            }else{

                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Не удалось обновить имя макета.'));

                $this->redirect(Url::toRoute(['templates/edit', 'id' => $model->id]));
            }

        }else{

            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Не удалось сохранить макет.'));

            $this->redirect(Url::toRoute(['templates/index']));
        }

        return;
    }



    public function actionEdit(){


        $this->layout='templates';

        $id = Yii::$app->request->get('id', -1);

        $template=Template::findOne(['id'=>$id]);

        if(!$template){

            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Макет не найден'));

            $this->redirect(Url::toRoute(['templates/index']));
        }


        $time_iterval=time()-$template->updated_at;

        $time_unit='сек';
        $time_value='1';
        $datetime=false;

        if($time_iterval<60){

            $time_unit='сек.';
            $time_value=$time_iterval;
            $datetime=false;


        }else if($time_iterval<60*60){

            $time_unit='мин.';
            $time_value=intval($time_iterval/60);
            $datetime=false;

        }else if($time_iterval<60*60*60){

            $time_unit='час.';
            $time_value=intval(($time_iterval/60/60).'');
            $datetime=false;
        }else{

            $datetime=true;
        }

        if($datetime){

            $updated_ago=Yii::t('app', 'Изменен {datetime}', ['datetime'=>date('d-m-Y', $template->updated_at)] );
        }else{

            $updated_ago=Yii::t('app', 'Изменен {time} {unit} назад', ['time'=>$time_value, 'unit'=>$time_unit] );
        }



        return $this->render('edit', ['template'=>$template, 'updated_ago'=>$updated_ago]);
    }


    public function actionViewSvg(){






        $this->layout='empty';

        $id = Yii::$app->request->get('id', -1);

        $template=Template::findOne(['id'=>$id]);



        if(!$template){

            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Макет не найден'));

            $this->redirect(Url::toRoute(['templates/index']));
        }

        $update_flag=false;

        $svg_path=UserUrl::templateThumb(false, $id).'.svg';

        $png_path=UserUrl::template(false).DIRECTORY_SEPARATOR.'thumbs';

        if(!file_exists(UserUrl::templateThumb(false, $id).'.svg')){
            $update_flag=true;
        }else{

            $svg_update_time=filectime($svg_path);

            if($template->updated_at>$svg_update_time){

                $update_flag=true;

            }
        }

      // $update_flag=true;


        if($update_flag) {

            $svg=str_replace('fill: transparent;', 'fill-opacity:0;', $template->svg);

            file_put_contents($svg_path, $svg);


            $batik_path=Yii::getAlias('@app').DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'batik'.DIRECTORY_SEPARATOR;

           // $cmds[]="java -d64 -Xms512m -Xmx4g -jar ".$batik_path."batik-rasterizer.jar -m image/jpg -w 350 -h 125 -q 0.99 -dpi 72 -d ".$png_path." ".$svg_path;

            exec("java -d64 -Xms512m -Xmx4g -jar ".$batik_path."batik-rasterizer.jar -m image/jpg -w 350 -h 125 -q 0.65 -dpi 72 -d ".$png_path." ".$svg_path);


        }



        $png=file_get_contents(UserUrl::templateThumb(false, $id).'.jpg');


        header('Content-type:image/png');

        echo $png;
    }





}
