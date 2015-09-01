<?php
namespace frontend\controllers;

use app\components\AlphaId;
use common\models\Cart;
use common\models\CartForm;
use common\models\Cover;
use common\models\Style;
use common\models\StyleForm;
use common\models\Template;
use common\models\User;
use Yii;

use yii\base\DynamicModel;
use yii\base\Exception;
use yii\filters\AccessControl;

use common\models\Photobook;
use common\models\PhotobookForm;
use common\models\PhotobookState;
use yii\web\UploadedFile;
use app\components\UserUrl;
use common\components\Utils;
use common\components\AmazonS3ResourceManager;




use yii\helpers\Url;



/**
 * Site controller
 */
class PhotobookApiController extends BaseController
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

    public function actionCreatePhotobook(){


        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if(Yii::$app->user->isGuest){

            return ['error'=>['msg'=>Yii::t('app', 'Ошибка доступа')]];

        }

        $model=new PhotobookForm();


        $title_line_1=Yii::$app->request->post('title_line_1', '');

        $title_line_2=Yii::$app->request->post('title_line_2', '');

        $title_line_3=Yii::$app->request->post('title_line_3', '');

        $title_line_4=Yii::$app->request->post('title_line_4', '');


        $user_id=Yii::$app->user->identity->getId();

        $model->user_id=$user_id;

        $model->title_line_1=$title_line_1;

        $model->title_line_2=$title_line_2;

        $model->title_line_3=$title_line_3;


        $model->title_line_4=$title_line_4;


        $line_1= (!empty($model->title_line_1)) ? $model->title_line_1 : ' ';

        $line_2= (!empty($model->title_line_2)) ? $model->title_line_2 : ' ';
        $line_3= (!empty($model->title_line_3)) ? $model->title_line_3 : ' ';

        $line_4= (!empty($model->title_line_3)) ? $model->title_line_4 : ' ';

        $model->name=$line_1." ".$line_2." ".$line_3;


        $model->status=Photobook::STATUS_NEW;
        $model->data='';
        $model->template='';

        if(empty($model->photos)){

            //Забиваем группа по умолчанию
            $model->photos=['Утро невесты'=>['photos'=>[], 'reversals'=>3], 'Прогулка'=>['photos'=>[], 'reversals'=>3], 'Текст'=>['type'=>'text', 'photos'=>[],'texts'=>[], 'reversals'=>3]];
        }



        $styles=Style::find()->where(['delete'=>0, 'status'=>1])->all();


        if(empty($styles)){


            return ['error'=>['msg'=>Yii::t('app', 'Не удалось создать новую фотокнигу. Так как нет ни одного опубликованого стиля.')]];


        }


        $model->style_id=$styles[0]->id;


        $covers=Cover::find()->where(['status'=>1])->all();

        if(empty($covers)){


            return ['error'=>['msg'=>Yii::t('app', 'Не удалось создать новую фотокнигу. Так как нет ни одной опубликованой обложки.')]];
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


            $result=$model->updateCoverWindowImageText('title_line_1', $title_line_1);



            Yii::getLogger()->log('updateCoverWindowImageText:'.print_r($result, true), YII_DEBUG);

            return ['response'=>['status'=>true, 'redirect'=>Url::toRoute(['photobooks/upload-photos', 'ref'=> AlphaId::id($photobook->user_id), 'id'=>AlphaId::id($photobook->id)])]];

        }else{

            return ['error'=>['msg'=>Yii::t('app', 'Не удалось сохранить фотокнигу.')]];
        }

    }

    public function actionCopyToUser(){


        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $ref = Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');

        $to_user_id=Yii::$app->request->get('user_id');

        if(!empty($ref) && !empty($id)){



            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);
            $model=new PhotobookForm();
            if($model->loadById($pb_id)){

                return $model->copyToUser($to_user_id);

            }else{
                return ['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
            }

        }else{

            return ['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }

    }


    public function actionUpdateCoverWindowImageText(){

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id= Yii::$app->request->get('id', null);
        $value= Yii::$app->request->post('value');

        $field_name= Yii::$app->request->get('field_name');

        if(empty($id)){
            return ['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }

        if(empty($field_name)){

            return ['error'=>['msg'=>Yii::t('app', 'field_name не задан')]];
        }


        $model=new PhotobookForm();
        if(!$model->loadById($id)){

            return ['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }


        return $model->updateCoverWindowImageText($field_name, $value);

    }


    public function actionMakePhotoZip(){

        $result=[];
        $this->layout='json';
        $ref = Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');

         if(!empty($ref) && !empty($id)){


             $user_id=AlphaId::id($ref, true);
             $pb_id=AlphaId::id($id, true);
             $model=new PhotobookForm();
             if($model->loadById($pb_id)){

                 $result=$model->makePhotoZip();

             }else{
                 $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
             }
         }else{

             $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
         }

        return $this->render('json', ['result'=>$result]);
    }


    public function actionAddGroup(){

        $result=[];
        $this->layout='json';
        $ref = Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');
        $group_name=Yii::$app->request->get('group');
        $after_group=Yii::$app->request->get('after_group','');

        if(!empty($ref) && !empty($id)){


            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);
            $model=new PhotobookForm();
            if($model->loadById($pb_id)){

                if(empty($group_name)){

                    $group_name=Yii::t('app', 'Группа 1');

                    $num=1;
                    while(isset($model->photos[$group_name])){
                        $num++;
                        $group_name=Yii::t('app', 'Группа {num}', ['num'=>$num]);
                    }
                }
                //Yii::getLogger()->log('Max:'.print_r($model->photos, true), YII_DEBUG);
                $result=$model->addGroup($group_name, $after_group);
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

    public function actionGetPhotos(){

        $result=[];
        $this->layout='json';
        $ref= AlphaId::id(Yii::$app->request->get('ref'), true);
        $id= AlphaId::id(Yii::$app->request->get('id'), true);
        $group_name=Yii::$app->request->get('group_name', '0');



        if(!empty($id)){

           // $user_id=$ref;//Yii::$app->user->identity->getId();

            $model=new PhotobookForm();
            if($model->loadById($id)){

                $photos=$model->photos;
                $list_photos=array();
                $list_texts=[];

                if($group_name!='0'){

                    if(!empty($photos[$group_name])){

                        foreach($photos[$group_name]['photos'] as $key=>$photo){

                            $list_photos[]=$photo;
                        }

                        if(!empty($photos[$group_name]['texts'])){


                            foreach($photos[$group_name]['texts'] as $text_id=>$textobj){

                                $list_texts[$text_id]=$textobj;
                            }

                        }
                    }else{

                        $result=['error'=>['msg'=>Yii::t('app', '')]];
                    }
                }else{

                    foreach($photos as $group_name=>$group){

                        foreach($photos[$group_name]['photos'] as $key=>$photo){

                            $list_photos[]=$photo;
                        }
                    }

                    if(!empty($photos[$group_name]['texts'])){


                        foreach($photos[$group_name]['texts'] as $text_id=>$textobj){

                            $list_texts[$text_id]=$textobj;
                        }

                    }
                }


                $result=['response'=>['photos'=>$list_photos, 'texts'=>$list_texts, 'status'=>true]];

            }else{
                $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
            }

        }else{

            $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }

        return $this->render('json', ['result'=>$result]);


    }

    public function actionDeletePhoto(){


        $this->layout='json';


        $ref = Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');

        $photo_id= Yii::$app->request->get('photo_id');

        if(!empty($ref) && !empty($id)){

            $model = new PhotobookForm();
            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);

            if($model->loadById($pb_id)){

                $result=$model->deletePhoto($photo_id, true);

            }else{
                $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
            }



        }else{

            $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }

        return $this->render('json', ['result'=>$result]);

    }

    public function actionDownloadLayouts(){

        $this->layout='json';


        $ref = Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');

        $cmds=[];

        if(!empty($ref) && !empty($id)){

            $model = new PhotobookForm();
            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);

           // Yii::getLogger()->log('post:'.print_r(Yii::$app->request->post(), true), YII_DEBUG);
            if($model->loadById($pb_id)){

                $pages=$model->data['pages'];

                $path_layouts=[];

                foreach($pages as $key=>$page){


                    if($page['action']=='print'){

                        $svg=$model->getPageSvgWithOriginalPhotos($page);

                        $svg_path=UserUrl::photobookLayouts(false, $pb_id, $user_id).DIRECTORY_SEPARATOR.'layout_'.(intval($key)+1).'.svg';
                        $jpg_path=UserUrl::photobookLayouts(false, $pb_id, $user_id).DIRECTORY_SEPARATOR.'layout_'.(intval($key)+1).'.jpg';




                        $svg=str_replace('fill: transparent;', 'fill-opacity:0;', $svg);
                        file_put_contents($svg_path, $svg);

                        unset($svg);

                       /* $image = new \Imagick();
                        $image->readImageBlob($svg);
                        $image->setImageFormat("jpeg");
                        $image->setImageCompressionQuality(100);
                        $image->resizeImage(8571, 4286, \imagick::FILTER_LANCZOS, 1);
                        $image->writeImage(UserUrl::photobookLayouts(false, $pb_id, $user_id).DIRECTORY_SEPARATOR.'layout_'.$key.'.jpg');*/

                        /*
                         * java -jar batik-rasterizer.jar -w 9000 -h 4500 -q 0.99 -dpi 300 layout_0.svg
                         */

                        $batik_path=Yii::getAlias('@app').DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'batik'.DIRECTORY_SEPARATOR;

                        $cmds[]="java -d64 -Xms512m -Xmx4g -jar ".$batik_path."batik-rasterizer.jar -m image/jpeg -w 8572 -h 4286 -q 0.99 -dpi 300 -d ".$jpg_path." ".$svg_path;

                        exec("java -d64 -Xms512m -Xmx4g -jar ".$batik_path."batik-rasterizer.jar -m image/jpeg -w 8572 -h 4286 -q 0.99 -dpi 300 -d ".$jpg_path." ".$svg_path);

                        if(file_exists($svg_path)) unlink($svg_path);



                        if(file_exists($jpg_path)){
                            $path_layouts[]=$jpg_path;
                        }
                    }
                }

                $zip_file_path=UserUrl::photobookLayouts(false, $pb_id, $user_id).DIRECTORY_SEPARATOR.'layouts_'.$pb_id.'.zip';
                $zip_file_url=UserUrl::photobookLayouts(true, $pb_id, $user_id).'/'.'layouts_'.$pb_id.'.zip';

                if(Utils::create_zip($path_layouts, $zip_file_path, true, '.jpg')){
                    foreach($path_layouts as $key=>$path_layout){
                        unlink($path_layout);
                    }
                }


                $batik_path=Yii::getAlias('@app').DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'batik'.DIRECTORY_SEPARATOR;
                $result=['response'=>['status'=>true, 'batik_path'=>$batik_path, 'url'=>$zip_file_url, 'cmds'=>$cmds]];

            }else{

                $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
            }
        }else{


            $result=['error'=>['msg'=>Yii::t('app', 'Данные не получены')]];
        }

        return $this->render('json', ['result'=>$result]);

    }


    public function actionUploadPhotoForReplace()
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

                        Yii::getLogger()->log('file:'. $file->name, YII_DEBUG);
                        Yii::getLogger()->log('extension:'. $file->extension, YII_DEBUG);
                        Yii::getLogger()->log('baseName:'. $file->baseName, YII_DEBUG);
                        Yii::getLogger()->log('tempName:'. $file->tempName, YII_DEBUG);

                        $photo_id=$file->baseName;

                        $file_path=UserUrl::photobookPhotos(false, $pb_id, $user_id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($photo_id, UserUrl::IMAGE_ORIGINAL);

                        if(file_exists($file_path)){
                            Yii::getLogger()->log('Есть этот файлик:'. $file->baseName, YII_DEBUG);
                            $current_image=Yii::$app->image->load($file_path);
                            $new_image=Yii::$app->image->load($file->tempName);

                            if($current_image->width!=$new_image->width && $current_image->height!=$new_image->height){

                                $result=['error'=>['msg'=>Yii::t('app', 'Не совподает разрешение для фото:{name}', ['name'=>$file->name])]];
                            }else{

                                if($file->saveAs($file_path))
                                {

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

                                            $file_resize_path=UserUrl::photobookPhotos(false, $pb_id, $user_id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($file->baseName, $key);
                                            $image->resize($size['width'],$size['height'],  $type);
                                            $image->save($file_resize_path);
                                        }
                                    }

                                    $model->setPhotoProcessed($photo_id, true);
                                    $file_thumb_path=UserUrl::photobookPhotos(false, $pb_id, $user_id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($file->baseName, UserUrl::IMAGE_THUMB);
                                    $mtime=filemtime($file_thumb_path);


                                    $pages=$model->getPagesIndexByPhotoId($photo_id);

                                    if(!empty($pages)){
                                        $newpages=$model->updatePages($model->style_id);
                                        $model->data['pages']=$newpages;
                                        $model->save();

                                    }
                                    /*foreach($pages as $key=>$page_index){


                                    }*/


                                    $result=['response'=>['status'=>true, 'filename'=>$file->name, 'photo_id'=>$file->baseName, 'mtime'=>$mtime, 'pages'=>$pages]];
                                }else{

                                    $result=['error'=>['msg'=>Yii::t('app', 'Не удалось записать фото:{name}', ['name'=>$file->name])]];
                                }
                            }


                            Yii::getLogger()->log('current width:'. $current_image->width, YII_DEBUG);
                            Yii::getLogger()->log('current height:'. $current_image->height, YII_DEBUG);


                            Yii::getLogger()->log('new width:'. $new_image->width, YII_DEBUG);
                            Yii::getLogger()->log('new height:'. $new_image->height, YII_DEBUG);


                        }else{



                            $result=['error'=>['msg'=>Yii::t('app', 'Фото с именем {name}, не найдено в этой фотокниге.', ['name'=>$file->name])]];
                        }



                        //$file->saveAs($file_path);

                        $paths=[];
                        $paths[]=$file_path;


                        //$result=['response'=>['status'=>true]];


                       /* foreach(UserUrl::$IMAGE_SIZE as $key=>$size){

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
                        }*/

                        //$result=$model->addPhoto($file_id, $group, true);


                        //Удаляем все файлы если не получилось сохранить
                        /*if(!empty($result['error'])){

                            foreach($paths as $path){

                                if(file_exists($path))
                                    unlink($path);
                            }
                        }*/


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

                        $name = $ref.'/pb/'.$id. '/photos/'.  UserUrl::imageFile($file_id, UserUrl::IMAGE_ORIGINAL);
                        Yii::$app->resourceManager->save($file, $name);

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



                                $file_tmp = new UploadedFile();

                                $file_tmp->tempName=$file_resize_path;



                                $name = $ref.'/pb/'.$id. '/photos/'. UserUrl::imageFile($file_id, $key);
                                Yii::$app->resourceManager->save($file_tmp, $name);

                                if(file_exists($file_resize_path)){
                                    unlink($file_resize_path);
                                }



                                $paths[]=$file_resize_path;
                            }
                        }

                        $result=$model->addPhoto($file_id, $group, true);

                        if(file_exists($file_path)){
                            unlink($file_path);
                        }





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
    }



    //update-image-pos-scale

    public function actionSetImagePosAndScale()
    {

        $result=[];
        $this->layout='json';
        $ref= Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');
        $page=Yii::$app->request->get('page',0);
        $place_num=intval(Yii::$app->request->get('place_num', 0));
        $pos_x=floatval(Yii::$app->request->get('pos_x', 0));
        $pos_y=floatval(Yii::$app->request->get('pos_y', 0));
        $scale=floatval(Yii::$app->request->get('scale', 1));


        if(!empty($id)){

            $model = new PhotobookForm();
            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);

            if($model->loadById($pb_id)){

                $result=$model->setImagePosAndScale($page, $place_num, $pos_x, $pos_y, $scale);

            }else{

                $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
            }

        }else{

            $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];

        }


        return $this->render('json', ['result'=>$result]);

        //$place_index=Yii::$app->request->get('place_index', '0');


    }

    public function actionDeletePage(){

        $result=[];
        $this->layout='json';
        $ref= Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');


        $page=Yii::$app->request->get('page',0);


        if(!empty($id)){

            $model = new PhotobookForm();
            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);

            if($model->loadById($pb_id)){

                $result=$model->deletePage($page);

            }else{

                $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];

            }



        }else{


            $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }


        return $this->render('json', ['result'=>$result]);


    }


    public function actionDeletePlaceholder(){

        $result=[];
        $this->layout='json';
        $ref= Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');

        $photo_id=Yii::$app->request->get('photo_id','');
        $page=Yii::$app->request->get('page',0);
        $place_num=intval(Yii::$app->request->get('place_num', 0));

        if(!empty($id)){

            $model = new PhotobookForm();
            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);

            if($model->loadById($pb_id)){

                $result=$model->deletePlaceholder($page, $place_num, $photo_id);

            }else{

                $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];

            }



        }else{


            $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }


        return $this->render('json', ['result'=>$result]);


    }




    public function actionAddPhoto(){

        $result=[];
        $this->layout='json';
        $ref= Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');

        $photo_id=Yii::$app->request->get('photo_id','');
        $page=Yii::$app->request->get('page',0);

        if(!empty($id)){

            $model = new PhotobookForm();
            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);

            if($model->loadById($pb_id)){

                $result=$model->addPhotoToPage($page, $photo_id);

            }else{

                $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];

            }



        }else{


            $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }


        return $this->render('json', ['result'=>$result]);


    }


    public function actionAddText(){

        $result=[];
        $this->layout='json';
        $ref= Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');


       // $page_index=Yii::$app->request->get('page_index',0);
        $text=Yii::$app->request->post('text','');

        if(!empty($id)){

            $model = new PhotobookForm();
            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);

            if($model->loadById($pb_id)){

                $result=$model->addText( $text);

            }else{

                $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];

            }



        }else{


            $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }


        return $this->render('json', ['result'=>$result]);


    }




//delete-link-for-customer
    public function actionDeleteLinkForCustomer(){


        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $ref= Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');


        if(!empty($id)){

            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);

            if($user_id!=Yii::$app->user->identity->getId()){

                return ['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
            }

            $model = new PhotobookForm();

            if($model->loadById($pb_id)){

                return $model->deleteLinkForCustomer();

            }else{

                return ['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
            }

        }else{


            return ['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }

    }


    public function actionSendEmailWithLinkToCustomer(){

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;



        $ref= Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');

        $email= Yii::$app->request->post('email');


        if(!empty($id)){

            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);

            if($user_id!=Yii::$app->user->identity->getId()){

                return ['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
            }

            $model = new PhotobookForm();

            if($model->loadById($pb_id)){


                try {

                    \Yii::$app->mailer->compose('@app/mail/customer_view_link', ['url' => Url::toRoute(['photobooks/view', 'key' => $model->view_access_key], true)])
                        ->setFrom(['passpartu2015@yandex.ru'=>'Sensation Album'])
                        ->setTo($email)
                        ->setSubject(Yii::t('app', 'Ссылка вашей будущей книги.'))
                        ->send();


                    return ['response'=>['status'=>true]];

                }catch(\Exception $e){

                    return ['error'=>['msg'=>$e->getMessage()]];
                }

            }else{

                return ['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
            }

        }else{


            return ['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }





    }


    public function actionPrepareGetLinkDialog(){


        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $ref= Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');

        if(!empty($id)){

            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);

            if($user_id!=Yii::$app->user->identity->getId()){

                return ['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
            }


            $model = new PhotobookForm();

            if($model->loadById($pb_id)){




                return ['response'=>['status'=>true, 'photobook'=>[
                    'id'=>$model->id,
                    'view_access_key'=>$model->view_access_key,
                    'status'=>$model->status,
                    'recieveLinkForCustomerUrl'=>Url::toRoute(['photobook-api/recieve-link-for-customer', 'id'=>$id, 'ref'=>$ref]),
                    'deleteLinkForCustomerUrl'=>Url::toRoute(['photobook-api/delete-link-for-customer', 'id'=>$id, 'ref'=>$ref]),
                    'sendEmailWithLinkToCustomerUrl'=>Url::toRoute(['photobook-api/send-email-with-link-to-customer', 'id'=>$id, 'ref'=>$ref]),
                    'viewLinkUrl'=>Url::toRoute(['photobooks/view', 'key'=> $model->view_access_key], true)

                ]]];

            }else{

                return ['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
            }



        }else{


            return ['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];


        }


    }


    public function actionRecieveLinkForCustomer(){


        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $ref= Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');


        if(!empty($id)){

            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);

            if($user_id!=Yii::$app->user->identity->getId()){

                return ['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
            }

            $model = new PhotobookForm();

            if($model->loadById($pb_id)){

                return $model->createLinkForCustomer();

            }else{

                return ['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
            }

        }else{


            return ['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }

    }

    public function actionSendToPrintFromCustomer(){


        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $key= Yii::$app->request->get('key');


        if(!empty($key)){


            $photobookState=PhotobookState::findOne(['view_access_key'=>$key]);

            if(!$photobookState){

                return ['error'=>['msg'=>Yii::t('app', 'Состояние не найдено')]];
            }


            $comments=PhotobookForm::photosDecode($photobookState->comments);

            $commentExists=false;

            foreach($comments as $index=>$comment){

                $val=trim($comment['comment']);

                if(!empty($val)){

                    $commentExists=true;
                }

            }

            if($commentExists){


                return ['error'=>['msg'=>Yii::t('app', 'Вы не можете отправить фотокнигу в печать, с комментриями. Сначало нужно отправить фотокнигу на доработку. Или удалите все комментарии и попробуйте еще раз.')]];

            }

            $photobookForm=new PhotobookForm();



            if(!$photobookForm->loadById($photobookState->photobook_id)){

                return ['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];

            }


            $photobookForm->status=Photobook::STATUS_SENT_TO_PRINT;

            $photobookForm->change_status_at=time();

            if(!$photobookForm->save()){

                return ['error'=>['msg'=>Yii::t('app', 'Не удалось записать в базу данных')]];
            }


            $photobookState->status=PhotobookState::STATUS_READY;

            $photobookState->update();


            return ['response'=>['status'=>true]];

        }else{


            return ['error'=>['msg'=>Yii::t('app', 'Состояние не найдено')]];

        }

    }

    public function actionSendToEditFromCustomer(){


        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $key= Yii::$app->request->get('key');


        if(!empty($key)){


            $photobookState=PhotobookState::findOne(['view_access_key'=>$key]);

            if(!$photobookState){

                return ['error'=>['msg'=>Yii::t('app', 'Состояние не найдено')]];
            }


            $comments=PhotobookForm::photosDecode($photobookState->comments);

            $commentExists=false;

            foreach($comments as $index=>$comment){

                $val=trim($comment['comment']);

                if(!empty($val)){

                    $commentExists=true;
                }

            }

            if(!$commentExists){


                return ['error'=>['msg'=>Yii::t('app', 'Вы не можете отправить фотокнигу на доработку, без заполненого минимум одиного комментария.')]];

            }

            $photobookForm=new PhotobookForm();



            if(!$photobookForm->loadById($photobookState->photobook_id)){

                return ['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];

            }


            $photobookForm->status=Photobook::STATUS_WAIT_EDIT_FROM_CUSTOMER;

            $photobookForm->change_status_at=time();

            if(!$photobookForm->save()){

                return ['error'=>['msg'=>Yii::t('app', 'Не удалось записать в базу данных')]];
            }


            $photobookState->status=PhotobookState::STATUS_WAIT_PHOTOGRAPH_EDIT;

            $photobookState->update();


            return ['response'=>['status'=>true]];

        }else{


            return ['error'=>['msg'=>Yii::t('app', 'Состояние не найдено')]];

        }

    }

    public function actionSaveCustomerComments(){

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $key= Yii::$app->request->get('key');
        $index= Yii::$app->request->post('index');
        $comment=Yii::$app->request->post('comment');


        if(!empty($key)){


            $photobookState=PhotobookState::findOne(['view_access_key'=>$key]);

            if(!$photobookState){

                return ['error'=>['msg'=>Yii::t('app', 'Состояние не найдено')]];
            }


            $comments=PhotobookForm::photosDecode($photobookState->comments);


            if(empty($comments[$index])){

                return ['error'=>['msg'=>Yii::t('app', 'Индекс не найден '.$index)]];
            }


            $comments[$index]['comment']=$comment;

            $photobookState->comments=PhotobookForm::photosEncode($comments);


            if(! $photobookState->update()){


                return ['error'=>['msg'=>Yii::t('app', 'Не удалось записать в базу')]];
            }else{

                return ['response'=>['status'=>true]];

            }




        }else{


            return ['error'=>['msg'=>Yii::t('app', 'Состояние не найдено')]];

        }





    }


    public function actionChangeText(){

        $result=[];
        $this->layout='json';
        $ref= Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');


        $page_index=Yii::$app->request->get('page_index',0);
        $text=Yii::$app->request->post('text','');

        if(!empty($id)){

            $model = new PhotobookForm();
            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);

            if($model->loadById($pb_id)){

                $result=$model->changeText($page_index, $text);

            }else{

                $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];

            }



        }else{


            $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }


        return $this->render('json', ['result'=>$result]);


    }


    public function actionChangeCover(){

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;



        $id= Yii::$app->request->get('id',0);
        $cover_id= Yii::$app->request->get('cover_id', 0);




        if(!empty($id)){

            $model = new PhotobookForm();



            if($model->loadById($id)){

                $model->cover_id=$cover_id;//($page_index, $text);

                if(!$model->save()){

                    return ['error'=>['msg'=>Yii::t('app', 'Ошибка записи в базу данных')]];

                }else{


                    return ['response'=>['status'=>true]];
                }

            }else{

                return ['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];

            }



        }else{


            return ['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }





    }


    public function actionMovePage(){

        $result=[];
        $this->layout='json';
        $ref= Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');

        $old_page_index=Yii::$app->request->get('old_page_index','');
        $new_page_index=Yii::$app->request->get('new_page_index','');

        if(!empty($id)){

            $model = new PhotobookForm();
            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);

            if($model->loadById($pb_id)){

                $result=$model->movePage($old_page_index, $new_page_index);

            }else{

                $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];

            }



        }else{


            $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }


        return $this->render('json', ['result'=>$result]);


    }


    public function actionAddNewPage(){

        $result=[];
        $this->layout='json';
        $ref= Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');

        $page_index=Yii::$app->request->get('page_index','');
        $photo_id=Yii::$app->request->get('photo_id','');

        if(!empty($id)){

            $model = new PhotobookForm();
            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);

            if($model->loadById($pb_id)){
                $result=$model->addNewPage($page_index, $photo_id);
            }else{
                $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
            }

        }else{


            $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }


        return $this->render('json', ['result'=>$result]);


    }

    public function actionChangeLayout(){

        $result=[];
        $this->layout='json';
        $ref= Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');
        $page_index=Yii::$app->request->get('page_index','');
        $action=Yii::$app->request->get('action','');

        if(!empty($id)){

            $model = new PhotobookForm();
            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);

            if($model->loadById($pb_id)){
                $result=$model->changeLayout($page_index);
            }else{
                $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
            }

        }else{
            $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }

        return $this->render('json', ['result'=>$result]);
    }



    public function actionChangeAction(){

        $result=[];
        $this->layout='json';
        $ref= Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');
        $page_index=Yii::$app->request->get('page_index','');
        $action=Yii::$app->request->get('action','');




        if(!empty($id)){

            $model = new PhotobookForm();
            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);

            if($model->loadById($pb_id)){

                $result=$model->changeAction($page_index, $action);

            }else{

                $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];

            }



        }else{


            $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }


        return $this->render('json', ['result'=>$result]);


    }




    public function actionSwapPhoto(){

        $result=[];
        $this->layout='json';
        $ref= Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');
        $new_place_num=Yii::$app->request->get('new_place_num','');
        $old_place_num=Yii::$app->request->get('old_place_num','');


        $page=Yii::$app->request->get('page',0);

        if(!empty($id)){

            $model = new PhotobookForm();
            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);

            if($model->loadById($pb_id)){

                $result=$model->swapPhoto($page, $new_place_num, $old_place_num);

            }else{

                $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];

            }



        }else{


            $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }


        return $this->render('json', ['result'=>$result]);


    }



    public function actionReplacePhoto(){

        $result=[];
        $this->layout='json';
        $ref= Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');
        $place_num=Yii::$app->request->get('place_num','');
        $photo_id=Yii::$app->request->get('photo_id','');
        $page=Yii::$app->request->get('page',0);

        if(!empty($id)){

            $model = new PhotobookForm();
            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);

            if($model->loadById($pb_id)){

                $result=$model->replacePhoto($page, $place_num, $photo_id);

            }else{

                $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];

            }



        }else{


            $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }


        return $this->render('json', ['result'=>$result]);


    }



    public function actionImageRotate(){

        $result=[];
        $this->layout='json';
        $ref= Yii::$app->request->get('ref');
        $id= Yii::$app->request->get('id');
        $photo_id=Yii::$app->request->get('photo_id','');
        $deg=intval(Yii::$app->request->get('deg', 0));
        $page=Yii::$app->request->get('page',-1);
        $place_num=intval(Yii::$app->request->get('place_num', -1));

        if(!empty($id)){

            $model = new PhotobookForm();
            $user_id=AlphaId::id($ref, true);
            $pb_id=AlphaId::id($id, true);

            if($model->loadById($pb_id)){

                $file_path=UserUrl::photobookPhotos(false, $pb_id, $user_id);
                $paths=[];
                $paths[]=$file_path;

                $ext='jpg';

                if($model->isText($photo_id)){

                    $ext='png';

                }

                $photo_o_path=$file_path.DIRECTORY_SEPARATOR. UserUrl::imageFile($photo_id, UserUrl::IMAGE_ORIGINAL, $ext);
                $image=Yii::$app->image->load($photo_o_path);
                $image->rotate($deg);
                $image->save($photo_o_path);

                foreach(UserUrl::$IMAGE_SIZE as $key=>$size){


                    if($key!=UserUrl::IMAGE_ORIGINAL){

                        $image=Yii::$app->image->load($photo_o_path);


                        $type= Yii\image\drivers\Image::HEIGHT;

                        if($size['width']>0 && $size['height']>0){

                            $type=Yii\image\drivers\Image::AUTO;
                        }

                        if($size['width']>0 && $size['height']==0){

                            $type=Yii\image\drivers\Image::WIDTH;
                        }

                        $file_resize_path=UserUrl::photobookPhotos(false, $pb_id, $user_id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($photo_id, $key, $ext);
                        $image->resize($size['width'],$size['height'],  $type);
                        $image->save($file_resize_path);

                        unset($image);
                        $image=null;
                    }
                }


                if($page<0 || $place_num<0){
                    $result=['response'=>['status'=>true, 'photo_id'=>$photo_id]];
                }else{
                    $result=$model->setImagePosAndScale($page, $place_num, 0, 0, 1);
                }

                $last_modified=filemtime($photo_o_path);
                if(!empty($result['response'])){
                    $result['response']['photo_id']=$photo_id;
                    $result['response']['page_index']=$page;
                    $result['response']['place_num']=$place_num;
                    $result['response']['last_modified']=$last_modified;
                }

            }else{

                $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
            }
        }else{
            $result=['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }

        return $this->render('json', ['result'=>$result]);
    }

    public function actionChangeStyle(){


        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $photobook_id= Yii::$app->request->get('photobook_id',0);

        $style_id= Yii::$app->request->get('style_id',0);


        $model = new PhotobookForm();

        if(empty($photobook_id)){

            return ['error'=>['msg'=>Yii::t('app', 'Не верный photobook_id')]];
        }


        if(!$model->loadById($photobook_id)){

            return ['error'=>['msg'=>Yii::t('app', 'Фотокнига не найдена')]];
        }


        $styleModel=new StyleForm();

        if(!$styleModel->loadById($style_id)){

            return ['error'=>['msg'=>Yii::t('app', 'Стиль не найден')]];
        }


        $model->style_id=$style_id;

        if(!$model->save()){

            return ['error'=>['msg'=>Yii::t('app', 'Ошибка записи в базу данных')]];
        }



        return ['response'=>['status'=>true]];
    }


    public function actionDeleteCartRow(){


        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        if(Yii::$app->user->isGuest){


            return ['error'=>['msg'=>Yii::t('app', 'Доступ закрыт')]];

        }

        $cart_id=Yii::$app->request->get('id',0);

        if($cart_id<=0){


            return ['error'=>['msg'=>Yii::t('app', 'Не верный id')]];

        }


        $carForm=new CartForm();


        if(!$carForm->loadById($cart_id)){

            return ['error'=>['msg'=>Yii::t('app', 'Запись не найдена')]];
        }

        if(Yii::$app->user->identity->role!=User::ROLE_ADMIN){

            if($carForm->user_id!=Yii::$app->user->identity->getId()){

                return ['error'=>['msg'=>Yii::t('app', 'Ошибка прав доступа')]];
            }
        }


        if($carForm->product_type==Cart::PRODUCT_PHOTOBOOK && !empty($carForm->product_info) && !empty($carForm->product_info['Photobook'])) {


            $pb_id=$carForm->product_info['Photobook']['id'];

            $photobook=new PhotobookForm();


            if($photobook->loadById($pb_id)){

                $photobook->setStatus(Photobook::STATUS_NEW);
            }

        }




        if(!Cart::deleteAll(['id'=>$cart_id])){

            return ['error'=>['msg'=>Yii::t('app', 'Не удалось удалить строку')]];

        }



        return ['response'=>['status'=>true]];







    }


    public function actionUpdateCartQuantity(){

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        if(Yii::$app->user->isGuest){


            return ['error'=>['msg'=>Yii::t('app', 'Доступ закрыт')]];

        }


        $cart_id=Yii::$app->request->get('id',0);

        $quantity=Yii::$app->request->post('quantity',0);


        if($quantity<=0){

            return ['error'=>['msg'=>Yii::t('app', 'Количчество должно быть больше 0')]];
        }


        if($cart_id<=0){


            return ['error'=>['msg'=>Yii::t('app', 'Не верный id')]];

        }


        $carForm=new CartForm();


        if(!$carForm->loadById($cart_id)){

            return ['error'=>['msg'=>Yii::t('app', 'Запись не найдена')]];
        }

        if(Yii::$app->user->identity->role!=User::ROLE_ADMIN){

            if($carForm->user_id!=Yii::$app->user->identity->getId()){

                return ['error'=>['msg'=>Yii::t('app', 'Ошибка прав доступа')]];
            }
        }




        $carForm->quantity=intval($quantity);



        if(!$carForm->save()){

            return ['error'=>['msg'=>Yii::t('app', 'Не удалось сохранить в базу данных')]];

        }



        return ['response'=>['status'=>true, 'sub_total'=>$carForm->price*$carForm->quantity]];





    }

    public function actionUpdateDeliveryAddress(){

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        if(Yii::$app->user->isGuest){


            return ['error'=>['msg'=>Yii::t('app', 'Доступ закрыт')]];

        }


        $value=Yii::$app->request->post('value',0);



        $user=User::findOne(['id'=>Yii::$app->user->identity->getId()]);

        if(empty($user)){

            return ['error'=>['msg'=>Yii::t('app', 'Доступ закрыт')]];
        }


        $user->delivery_address=$value;


        if(!$user->update(false)){


            return ['error'=>['msg'=>Yii::t('app', 'Не удалось сохранить в базу данных')]];

        }



        return ['response'=>['status'=>true]];





    }









}
