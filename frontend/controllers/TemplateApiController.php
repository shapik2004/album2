<?php
namespace frontend\controllers;

use app\components\AlphaId;
use Yii;

use yii\base\DynamicModel;
use yii\filters\AccessControl;

use common\models\Template;

use yii\web\UploadedFile;
use app\components\UserUrl;


use yii\helpers\Url;
/**
 * Site controller
 */
class TemplateApiController extends BaseController
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


    public  function actionUploadFu2(){


        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $type= Yii::$app->request->get('type', '1_L');


        $template_id= Yii::$app->request->get('id', '');



        $template=Template::findOne(['id'=>$template_id]);

        if(!$template){

            return ['error'=>['msg'=>Yii::t('app', 'Шаблон не найден')]];

        }



        $model=new DynamicModel();

        //$model->formName();


        $file = UploadedFile::getInstance($model, 'fu2');


        if(!$file){


            return ['error'=>['msg'=>Yii::t('app', 'Файл не получен')]];


        }


        if($file->size<=0){

            return ['error'=>['msg'=>Yii::t('app', 'Файл пустой')]];

        }

        $fu2_path=UserUrl::fu2(false, $template_id, $type);


        $file->saveAs($fu2_path);



        return ['response'=>['status'=>true, 'type'=>$type]];


    }



    public function actionGetTemplatesByPhCount(){

        $result=[];
        $this->layout='json';

        $ph_count= Yii::$app->request->get('ph_count',0);


        if($ph_count>0){

            $conditions=[];

            $conditions['count_placeholder']=$ph_count;

            $templates = Template::find()->where($conditions)->all();

            $newtemplates=[];
            foreach($templates as $key=>$template){

                $newtemplates[]=[
                    'url'=>Url::toRoute(['templates/view-svg', 'id'=>$template->id]),
                    'id'=>$template->id
                ];
            }

            $result=['response'=>['status'=>true, 'templates'=>$newtemplates]];

        }else{

            $result=['error'=>['msg'=>Yii::t('app', 'Не верный параметр')]];
        }

        return $this->render('json', ['result'=>$result]);
    }

    public function actionSaveChanges(){

        $result=[];
        $this->layout='json';

        $id= Yii::$app->request->get('id',0);

        $json= Yii::$app->request->post('json','');

        $svg= Yii::$app->request->post('svg','');


        //$json_text= Yii::$app->request->post('json_text','');

        //$svg_text= Yii::$app->request->post('svg_text','');


        if(!empty($id)){

           // $template=new Template();
            $template=Template::findOne(['id'=>$id]);

            $objects=json_decode($json, true);

            $ph=0;
            $passepartout=true;


            $text_object=false;
            if($json!=''){

                $objects=json_decode($json, true);

                foreach($objects['objects'] as $key=>$object ){

                    if(!empty($object) && $object['type']=='placeholder'){

                        if(isset($object['object_maybe_as_text']) && $object['object_maybe_as_text']) $text_object=true;

                        $ph++;
                    }
                }
            }

            $template->id=$id;
            $template->json=$json;
            $template->svg=$svg;

            //$template->json_text=$json_text;
            //$template->svg_text=$svg_text;

            $template->count_placeholder=$ph;
            $template->passepartout=$passepartout;
            $template->text_object=$text_object;

            if($template->name==''){

                $template->name='#'.$id;
            }





            if($template->update(false)){


                $svg_path=UserUrl::templateThumb(false, $id).'.svg';

                $png_path=UserUrl::template(false).DIRECTORY_SEPARATOR.'thumbs';



                $svg=str_replace('fill: transparent;', 'fill-opacity:0;', $template->svg);

                file_put_contents($svg_path, $svg);


                $batik_path=Yii::getAlias('@app').DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'batik'.DIRECTORY_SEPARATOR;

                // $cmds[]="java -d64 -Xms512m -Xmx4g -jar ".$batik_path."batik-rasterizer.jar -m image/jpg -w 350 -h 125 -q 0.99 -dpi 72 -d ".$png_path." ".$svg_path;

                exec("java -d64 -Xms512m -Xmx4g -jar ".$batik_path."batik-rasterizer.jar -m image/jpg -w 350 -h 125 -q 0.65 -dpi 72 -d ".$png_path." ".$svg_path);





                $updated_ago=Yii::t('app', 'Все изменения сохранены' );



                $result=['response'=>['status'=>true, 'changed_datetime'=>date('d-m-Y H:i:s', $template->updated_at), 'updated_ago'=>$updated_ago]];

            }else{

                $result=['error'=>['msg'=>Yii::t('app', 'Не удалось сохранить' ), 'json'=>$json]];
            }

        }else{

            $result=['error'=>['msg'=>Yii::t('app', 'Неверный id макета')]];
        }

        return $this->render('json', ['result'=>$result]);


    }

    public function actionUpdateName(){

        //$result=[];

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id= Yii::$app->request->get('id',0);

        $name= Yii::$app->request->post('name',0);

        if (\Yii::$app->user->isGuest) {
            return ['error'=>['msg'=>Yii::t('app', 'Ошибка авторизации.')]];
        }


        if(\Yii::$app->user->identity->role!=\common\models\User::ROLE_ADMIN){

            return ['error'=>['msg'=>Yii::t('app', 'Необходимы права администратора.')]];
        }


        $template=Template::findOne(['id'=>$id]);

        if(!$template){

            return ['error'=>['msg'=>Yii::t('app', 'Шаблон не найден.')]];
        }

        $template->id=$id;
        $template->name=$name;


        if(!$template->update(false)){

            return ['error'=>['msg'=>Yii::t('app', 'Ошибка сохранения шаблона.')]];

        }else{

            return ['response'=>['status'=>true]];
        }



    }


    public function actionPublish(){

        //$result=[];

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id= Yii::$app->request->get('id',0);

        $publish= Yii::$app->request->post('publish',0);

        if (\Yii::$app->user->isGuest) {
            return ['error'=>['msg'=>Yii::t('app', 'Ошибка авторизации.')]];
        }


        if(\Yii::$app->user->identity->role!=\common\models\User::ROLE_ADMIN){

            return ['error'=>['msg'=>Yii::t('app', 'Необходимы права администратора.')]];
        }


        $template=Template::findOne(['id'=>$id]);

        if(!$template){

            return ['error'=>['msg'=>Yii::t('app', 'Шаблон не найден.')]];
        }

        $template->id=$id;
        $template->publish=$publish;


        if(!$template->update(false)){

            return ['error'=>['msg'=>Yii::t('app', 'Ошибка сохранения шаблона.')]];

        }else{

            return ['response'=>['status'=>true]];
        }



    }

    /*public function actionChangeGroupName(){

        $result=[];
        $this->layout='json';
        $ref = Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');
        $old_group_name=Yii::$app->request->get('oldgroup');
        $new_group_name=Yii::$app->request->get('newgroup');

        if(!empty($ref) && !empty($id)){

            if(!empty($old_group_name) && !empty($new_group_name)){
                $user_id=AlphaId::id($ref, true);
                $pb_id=AlphaId::id($id, true);
                $model=new PhotobookForm();
                if($model->loadById($pb_id)){

                    Yii::getLogger()->log('Max:'.print_r($model->photos, true), YII_DEBUG);
                    $result=$model->changeGroupName($new_group_name, $old_group_name);
                }else{
                    $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
                }
            }else{
                $result=['error'=>['msg'=>Yii::t('app', 'Не верно заданные параметры')]];
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
        $user_id= AlphaId::id(Yii::$app->request->get('ref'), true);
        $id= AlphaId::id(Yii::$app->request->get('id'), true);
        $group=Yii::$app->request->get('group');
        //$reversals=Yii::$app->request->get('reversals', 3);

        if(!empty($id)){

            if(!empty($group)){
                // $user_id=Yii::$app->user->identity->getId();


                $model=new PhotobookForm();
                if($model->loadById($id)){


                    $result=$model->deleteGroup($group);

                }else{
                    $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
                }
            }else{
                $result=['error'=>['msg'=>Yii::t('app', 'Не верно заданные параметры')]];
            }
        }else{

            $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }

        return $this->render('json', ['result'=>$result]);

    }


    public function actionChangeReversals(){

        $result=[];
        $this->layout='json';
        $user_id= AlphaId::id(Yii::$app->request->get('ref'), true);
        $id= AlphaId::id(Yii::$app->request->get('id'), true);
        $group=Yii::$app->request->get('group');
        $reversals=Yii::$app->request->get('reversals', 3);

        if(!empty($id)){

            if(!empty($group)){
               // $user_id=Yii::$app->user->identity->getId();


                $model=new PhotobookForm();
                if($model->loadById($id)){


                    $result=$model->changeReversals($group, $reversals);

                }else{
                    $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
                }
            }else{
                $result=['error'=>['msg'=>Yii::t('app', 'Не верно заданные параметры')]];
            }
        }else{

            $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }

        return $this->render('json', ['result'=>$result]);

    }

    public function actionChangeName(){

        $result=[];
        $this->layout='json';
        $ref= AlphaId::id(Yii::$app->request->get('ref'), true);
        $id= AlphaId::id(Yii::$app->request->get('id'), true);
        $new_name=Yii::$app->request->get('name');

        if(!empty($id)){

            if(!empty($new_name)){
                $user_id=Yii::$app->user->identity->getId();


                $model=new PhotobookForm();
                if($model->loadById($id)){


                    $result=$model->changeName($new_name);

                }else{
                    $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
                }
            }else{
                $result=['error'=>['msg'=>Yii::t('app', 'Не верно заданные параметры')]];
            }
        }else{

            $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }

        return $this->render('json', ['result'=>$result]);

    }


    public function actionUpload()
    {
        $this->layout='json';


        $ref = Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');
        $group= Yii::$app->request->get('group');

        if(!empty($ref) && !empty($id)){

            $model = new PhotobookForm();
            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);

            Yii::getLogger()->log('post:'.print_r(Yii::$app->request->post(), true), YII_DEBUG);
            if($model->loadById($pb_id)){

                $file = UploadedFile::getInstance($model, 'photo');

                if($file){

                    if($file->size!==0){

                        Yii::getLogger()->log('files:'.print_r($file, true), YII_DEBUG);

                        Yii::getLogger()->log('files:'.print_r($_FILES, true), YII_DEBUG);

                        $file_id=AlphaId::id(rand(10000000000, 9999999999999));
                        $file_path=UserUrl::photobookPhotos(false, $pb_id, $user_id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, UserUrl::IMAGE_ORIGINAL);
                        $file->saveAs($file_path);

                        $paths=[];
                        $paths[]=$file_path;


                        foreach(UserUrl::$IMAGE_SIZE as $key=>$size){

                            if($key!=UserUrl::IMAGE_ORIGINAL){

                                $image=Yii::$app->image->load($file_path);

                                $type= Yii\image\drivers\Image::HEIGHT;

                                if($size['width']>0 && $size['height']>0){

                                    $type=Yii\image\drivers\Image::AUTO;
                                }

                                if($size['width']>0 && $size['height']==0){

                                    $type=Yii\image\drivers\Image::WIDTH;
                                }

                                $file_resize_path=UserUrl::photobookPhotos(false, $pb_id, $user_id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, $key);
                                $image->resize($size['width'],$size['height'],  $type);
                                $image->save($file_resize_path);

                                $paths[]=$file_resize_path;
                            }
                        }

                        $result=$model->addPhoto($file_id, $group, true);


                        //Удаляем все файлы если не получилось сохранить
                        if(!empty($result['error'])){

                            foreach($paths as $path){

                                if(file_exists($path))
                                unlink($path);
                            }
                        }


                    }else{

                        $result=['error'=>['msg'=>Yii::t('app', 'Данные не получены')]];
                    }

                }else{

                    $result=['error'=>['msg'=>Yii::t('app', 'Данные не получены')]];
                }


            }else{
                $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
            }



        }else{

            $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }

        return $this->render('json', ['result'=>$result]);
    }*/





}
