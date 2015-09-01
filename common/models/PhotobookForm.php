<?php
namespace common\models;


use Yii;
use yii\base\Exception;
use yii\base\Model;

use common\models\Photobook;
use common\models\PhotobookState;
use frontend\widgets\ThumbInGroup;
use common\components\Utils;
use app\components\UserUrl;
use app\components\AlphaId;
use yii\helpers\Url;
use frontend\widgets\UploadPhotosGroup;
use common\models\Style;
use common\models\StyleForm;
use common\models\Template;
use frontend\widgets\ImagePlaceholderReplacer;
use frontend\widgets\TextPlaceholderReplacer;
use frontend\widgets\ImageBackgroundReplacer;
use yii\web\UploadedFile;




/**
 * Photobook form
 */
class PhotobookForm extends Model
{
    public $id = null;
    public $user_id;
    public $name = 'Новая фотокнига';
    public $status = Photobook::STATUS_NEW;
    public $data =[];
    public $template='';
    public $photos=[];
    public $style_id=0;
    public $cover_id=0;
    public $title_line_1="";
    public $title_line_2="";
    public $title_line_3="";
    public $title_line_4="";
    public $change_status_at=0;
    public $view_access_key;

    public $invoice_id;

    public $photos_zip_hash=null;




    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [


            [['template', 'photos', 'name', 'data', 'title_line_1', 'title_line_2', 'title_line_3', 'title_line_4', 'view_access_key'], 'string'],
            ['name', 'filter', 'filter' => 'trim'],
            [['user_id', 'status', 'style_id', 'cover_id', 'change_status_at', 'invoice_id'], 'integer'],




        ];
    }





    public function loadByUserId($user_id){

        $photobook=Photobook::findByUserId($user_id);
        if(!empty($photobook)){
            $this->load( $photobook->toArray(), '');

            if($this->data=='') $this->data=PhotobookForm::photosEncode([]);
            $this->photos=PhotobookForm::photosDecode($this->photos);
            $this->data=PhotobookForm::photosDecode($this->data);
            return true;
        }

        return false;
    }

    public function loadById($id){

        $photobook=Photobook::findOne(['id'=>$id]);
        if(!empty($photobook)){

            $this->load( $photobook->toArray(), '');
            $this->id=$id;
            if($this->data=='') $this->data=PhotobookForm::photosEncode([]);
            $this->photos=PhotobookForm::photosDecode($this->photos);
            $this->data=PhotobookForm::photosDecode($this->data);

            Yii::getLogger()->log('photobook_id:'.$this->id, YII_DEBUG);
            Yii::getLogger()->log('loadById_json:'.print_r($this->photos, true), YII_DEBUG);
            return true;
        }

        return false;
    }

    public function copyToUser($to_user_id, $status = Photobook::STATUS_NEW){


        $newPhotobookForm=new PhotobookForm();

        $newPhotobookForm->user_id=$to_user_id;

        $newPhotobookForm->name=($this->user_id==$to_user_id) ? Yii::t('app', 'Копия ').$this->name : $this->name;


        $newPhotobookForm->status=$status;//Photobook::STATUS_NEW;


        $newPhotobookForm->data=$this->data;

        $newPhotobookForm->template=$this->template;

        $newPhotobookForm->photos=$this->photos;

        $newPhotobookForm->style_id=$this->style_id;


        $newPhotobookForm->cover_id=$this->cover_id;


        $newPhotobookForm->title_line_1=$this->title_line_1;

        $newPhotobookForm->title_line_2=$this->title_line_2;

        $newPhotobookForm->title_line_3=$this->title_line_3;

        $newPhotobookForm->title_line_4=$this->title_line_4;

        $newPhotobookForm->change_status_at=time();

        $newPhotobookForm->view_access_key=null;

        $newPhotobookForm->invoice_id=null;

        $newPhotobookForm->photos_zip_hash=null;



        if(!$newPhotobookForm->save()){


            return ['error'=>['msg'=>Yii::t('app', 'Не удалось записать в базу данных')]];
        }

        $new_pb_id=$newPhotobookForm->id;
        $new_user_id=$to_user_id;


        $src_path=UserUrl::photobook(false, $this->id, $this->user_id);

        $dst_path=UserUrl::photobook(false, $newPhotobookForm->id, $newPhotobookForm->user_id);


        try {
            Utils::recurse_copy($src_path, $dst_path);

            //fix window_text and tracing
            $src_window_text=UserUrl::photobookWindowText(false, $newPhotobookForm->id, $newPhotobookForm->user_id ).DIRECTORY_SEPARATOR.UserUrl::imageFile($this->id, UserUrl::IMAGE_ORIGINAL, 'png');

            $dst_window_text=UserUrl::photobookWindowText(false, $newPhotobookForm->id, $newPhotobookForm->user_id ).DIRECTORY_SEPARATOR.UserUrl::imageFile($newPhotobookForm->id, UserUrl::IMAGE_ORIGINAL, 'png');

            if(file_exists($src_window_text)) {

                copy($src_window_text, $dst_window_text);

                unlink($src_window_text);

            }



            $src_tracing=UserUrl::photobookTracingText(false, $newPhotobookForm->id, $newPhotobookForm->user_id ).DIRECTORY_SEPARATOR.UserUrl::imageFile($this->id, UserUrl::IMAGE_ORIGINAL, 'png');

            $dst_tracing=UserUrl::photobookTracingText(false, $newPhotobookForm->id, $newPhotobookForm->user_id ).DIRECTORY_SEPARATOR.UserUrl::imageFile($newPhotobookForm->id, UserUrl::IMAGE_ORIGINAL, 'png');

            if(file_exists($src_tracing)) {

                copy($src_tracing, $dst_tracing);

                unlink($src_tracing);

            }


            return ['response'=>['status'=>true, 'id'=>$new_pb_id, 'user_id'=>$new_user_id, 'redirect'=>Url::toRoute(['photobooks/index',  'status'=>Photobook::STATUS_NEW])]];


        }catch (\Exception $e){

            return ['error'=>['msg'=>$e->getMessage()]];

        }

    }


    public function loadByViewAccessKey($view_access_key){

        $photobook=Photobook::findOne(['view_access_key'=>$view_access_key]);
        if(!empty($photobook)){

            $this->load( $photobook->toArray(), '');
            $this->id=$photobook->id;
            if($this->data=='') $this->data=PhotobookForm::photosEncode([]);
            $this->photos=PhotobookForm::photosDecode($this->photos);
            $this->data=PhotobookForm::photosDecode($this->data);

            Yii::getLogger()->log('photobook_id:'.$this->id, YII_DEBUG);
            Yii::getLogger()->log('loadById_json:'.print_r($this->photos, true), YII_DEBUG);
            return true;
        }

        return false;
    }

    public function deleteLinkForCustomer(){



        if(!empty( $this->view_access_key)){

            //если у нас было состояние до этого активное, то после удаления ссылки возможно нужно сменить статус



            $photobookState=PhotobookState::findOne(['view_access_key'=>$this->view_access_key]);

            if($photobookState){


                $photobookState->status=PhotobookState::STATUS_CLOSE;

                $photobookState->update();
            }

        }


        $this->view_access_key=null;

        $this->status=Photobook::STATUS_NEW;

        $this->change_status_at=time();


        if($this->save()){

            return ['response'=>['status'=>true]];
        }else{


            return ['error'=>['msg'=>Yii::t('app', 'Не удалось удалить')]];
        }
    }


    public function getProductInfo(){



        $cover=new CoverForm();


        if(!$cover->loadById( $this->cover_id)){


            return ['error'=>['msg'=>Yii::t('app', 'Обложка для книги не найдена')]];
        }


        $style=new StyleForm();


        if(!$style->loadById( $this->style_id)){

            return ['error'=>['msg'=>Yii::t('app', 'Стиль для книги не найдена')]];
        }


        if(empty($this->data)){


            return ['error'=>['msg'=>Yii::t('app', 'Ошибка данных')]];
        }


        if(empty($this->data['pages'])){


            return ['error'=>['msg'=>Yii::t('app', 'Страниц не найдено')]];
        }


        $pages=$this->data['pages'];
        $print_count=0;
        foreach($pages as $key=>$page){

            if($key==0 || $key==count($pages)-1)  continue;

            if($page['action']=='print'){

                $print_count++;

            }
        }


        if($print_count<=0){

            return ['error'=>['msg'=>Yii::t('app', 'Разворотов для печати не найдено')]];
        }


        $base_price=(count($pages)-2)*($style->price_spread);

        $cover_price_sign=$cover->price_sign;

        $total_price=$base_price;

        if($cover_price_sign=="="){

            $total_price=($cover->price);

        }else if($cover_price_sign=="+"){

            $total_price+=($cover->price);

        }else if($cover_price_sign=="-"){

            $total_price-=($cover->price);
        }




        $text_info=Yii::t('app', 'Код товара:').' '.$this->id.'. '.Yii::t('app', 'Фотокнига:').' '.$this->name.', '.Yii::t('app', 'обложка:').' '.
                   $cover->material_type.'/'.$cover->name.', '.Yii::t('app', '1 калька').', '.$print_count.' '.
                   Utils::getInclinationByNumber($print_count, ['разворот', 'разворота', 'разворотов']);


        return ['response'=>['status'=>true, 'price'=>$total_price, 'text_info'=>$text_info]];

    }

    private function createPhotobookState(){

        //$view_access_key='';

        $number=time()+rand(0,9999999);

        $view_access_key=AlphaId::id($this->user_id).AlphaId::id($number);


        $photobookState=new PhotobookState();
        $photobookState->user_id=$this->user_id;
        $photobookState->photobook_id=$this->id;
        $photobookState->status=PhotobookState::STATUS_WAIT_CUSTOMER_COMMENTS;
        $photobookState->data=PhotobookForm::photosEncode($this->data);
        //$photobookState->template=$this->template;
        $photobookState->style_id=$this->style_id;
        $photobookState->cover_id=$this->cover_id;
        $photobookState->title_line_1=$this->title_line_1;
        $photobookState->title_line_2=$this->title_line_2;
        $photobookState->title_line_3=$this->title_line_3;
        $photobookState->title_line_4=$this->title_line_4;
        //$photobookState->photos_zip_hash=$this->photos_zip_hash;

        //$photobookState->change_status_at=$this->change_status_at;
        $photobookState->view_access_key=$view_access_key;


        $this->photos=$this->addTextGroupIfNotExists($this->photos);
        $photobookState->photos=PhotobookForm::photosEncode($this->photos);


        $photobookState->comments=[];


        if(empty($this->data['pages'])){

            return false;
        }

        //Создаем массив данных где будут хранится коменты к каждой странице

        if(empty($photobookState->comments)){

           $comments=[];


            for($i=0; $i<count($this->data['pages']); $i++){


                $type="spreads";


                $page_name='Комментарий к развороту '.$i;

                if($i==0){

                    $type="cover";
                    $page_name='Комментарий к обложке';
                }


                if($i==count($this->data['pages'])-1){

                    $type="total";
                    $page_name='Общий комментарий';
                }

                $comments[$i]=['title'=>$page_name, 'comment'=>'', 'type'=>$type];

                if($type=='spreads'){

                    $comments[$i]['print']=true;

                }




                $photobookState->comments=$comments;
            }
        }



        $photobookState->comments= PhotobookForm::photosEncode($photobookState->comments);
        //Yii::getLogger()->log('save:', YII_DEBUG);


        if($photobookState->save()){


            return $view_access_key;

        }else{

            return false;
            //Yii::getLogger()->log('save error', YII_DEBUG);
        }

    }


    public function createLinkForCustomer(){


        $view_access_key=$this->createPhotobookState();


        if($view_access_key===false){

            return ['error'=>['msg'=>Yii::t('app', 'Не удалось создать ссылку')]];
        }


        $this->view_access_key=$view_access_key;

        $this->status=Photobook::STATUS_SENT_TO_CUSTOMER;
        $this->change_status_at=time();


        if($this->save()){

            return ['response'=>['status'=>true, 'view_access_key'=> $this->view_access_key, 'url'=>Url::toRoute(['photobooks/view', 'key'=> $this->view_access_key], true)]];
        }else{


            return ['error'=>['msg'=>Yii::t('app', 'Не удалось создать ссылку')]];
        }


    }


    public function updateCoverWindowImageText($field_name, $value){


        $this->$field_name=$value;

        /*if(!$this->save()){

            return ['error'=>['msg'=>Yii::t('app', 'Ошибка записи в базу-данных')]];
        }*/

        //$this->loadById($this->id);


        $style=Style::findOne(['id'=>$this->style_id]);

        if(empty($style)){


            return ['error'=>['msg'=>Yii::t('app', 'Стиль для фотокниги не найден')]];
        }



        $font=Font::findOne(['id'=>$style->font_id]);


        if(empty($font)){

            return ['error'=>['msg'=>Yii::t('app', 'Шрифт для фотокниги не найден')]];
        }


        $cover=Cover::findOne(['id'=>$this->cover_id]);


        if(empty($cover)){

            return ['error'=>['msg'=>Yii::t('app', 'Обложка для фотокниги не найден')]];
        }


        $font_path=UserUrl::font(false).DIRECTORY_SEPARATOR.UserUrl::fontFile($font->file);


        $line_1= (!empty($this->title_line_1)) ? $this->title_line_1 : ' ';

        $line_2= (!empty($this->title_line_2)) ? $this->title_line_2 : ' ';
        $line_3= (!empty($this->title_line_3)) ? $this->title_line_3 : ' ';

        $line_4= (!empty($this->title_line_3)) ? $this->title_line_4 : ' ';

        $this->name=$line_1." ".$line_2." ".$line_3;

        $this->save();


        $text=$line_1."\n".$line_2."\n".$line_3;

        $text_image_content=Utils::makeTextImage($text, $cover->window_width, $cover->window_height, '#000000', $font_path, 2, 72);

        $text_image_path=UserUrl::photobookWindowText(false, $this->id, $this->user_id).DIRECTORY_SEPARATOR.UserUrl::imageFile($this->id, UserUrl::IMAGE_ORIGINAL, 'png');
        $text_image_url=UserUrl::photobookWindowText(true, $this->id, $this->user_id)."/".UserUrl::imageFile($this->id, UserUrl::IMAGE_ORIGINAL, 'png');










        $user_pb_path=UserUrl::photobook(false,$this->id, $this->user_id);

        if(file_exists($user_pb_path)){

            Yii::getLogger()->log('updateCoverWindowImageText exists user_pb_path:'.$user_pb_path, YII_DEBUG);

        }else{

            Yii::getLogger()->log('updateCoverWindowImageText NOT exists user_pb_path:'.$user_pb_path, YII_DEBUG);
        }

        // UserUrl::createNonexistentDirInPath(UserUrl::photobookWindowText(, 'pb'.DIRECTORY_SEPARATOR.$photobook_id)


        file_put_contents($text_image_path, $text_image_content);


        $file_tmp = new UploadedFile();

        $file_tmp->tempName=$text_image_path;



        $name = AlphaId::id($this->user_id).'/pb/'.AlphaId::id($this->id). '/window_text/'. UserUrl::imageFile($this->id, UserUrl::IMAGE_ORIGINAL, 'png');
        Yii::$app->resourceManager->save($file_tmp, $name);


        unlink($text_image_path);



        $tracing_text=$line_1."\n".$line_2."\n".$line_3."\n"." "."\n"." "."\n"." "."\n"." "."\n"." ".$line_4;

        $tracing_text_image_content=Utils::makeTextImage($tracing_text, 350, 250, '#000000', $font_path, 15, 72);


        $tracing_text_image_path=UserUrl::photobookTracingText(false, $this->id, $this->user_id).DIRECTORY_SEPARATOR.UserUrl::imageFile($this->id, UserUrl::IMAGE_ORIGINAL, 'png');
        $tracing_text_image_url=UserUrl::photobookTracingText(true, $this->id, $this->user_id).DIRECTORY_SEPARATOR.UserUrl::imageFile($this->id, UserUrl::IMAGE_ORIGINAL, 'png');


        file_put_contents($tracing_text_image_path, $tracing_text_image_content);


        $file_tmp = new UploadedFile();

        $file_tmp->tempName=$tracing_text_image_path;



        $name = AlphaId::id($this->user_id).'/pb/'.AlphaId::id($this->id). '/tracing/'. UserUrl::imageFile($this->id, UserUrl::IMAGE_ORIGINAL, 'png');
        Yii::$app->resourceManager->save($file_tmp, $name);


        unlink($tracing_text_image_path);

        return

            [
                'response'=>[
                    'status'=>true,
                    'value'=>$value,
                    'text_image_url'=>$text_image_url,
                    'tracing_text_image_url'=>$tracing_text_image_url,
                    'window_offset_x'=>$cover->window_offset_x,
                    'window_offset_y'=>$cover->window_offset_y,
                    'window_width'=>$cover->window_width,
                    'window_height'=>$cover->window_height,
                    'font_path'=>$font_path,
                    'text'=>$text,
                    'name'=>$this->name
                ]
            ];

    }

    //public function

    public function addGroup($group_name, $after_group, $reversals=3){

        if(isset($this->photos[$group_name])){

            $result=['error'=>['msg'=>Yii::t('app', 'Такая группа уже существует')]];

        }else{

            $user_id=$this->user_id;
            $ref=AlphaId::id($user_id);
            $id=AlphaId::id($this->id);
            $pb_id=$id;



            if(!empty($after_group) && isset($this->photos[$after_group])){

                $newphotos=[];

                foreach($this->photos as $g_name=>$group){

                    $newphotos[$g_name]=$group;

                    if($g_name==$after_group){

                        $newphotos[$group_name]=['photos'=>[], 'reversals'=>$reversals ];
                    }
                }

                $this->photos=$newphotos;

            }else{
                $this->photos[$group_name]=['photos'=>[], 'reversals'=>$reversals ];
            }


            if($this->save()){

                $current_group=UploadPhotosGroup::widget([

                    'group_name'=>$group_name,
                    'change_group_name_template_url' => Url::toRoute(['photobook-api/change-group-name', 'ref'=>$ref, 'id'=>$id, 'oldgroup'=>'oldgroupname', 'newgroup'=>'newgroupname']),
                    'upload_files_url'=>Url::toRoute(['photobook-api/upload', 'ref'=>$ref, 'id'=>$id, 'group'=>$group_name]),
                    'upload_files_template_url'=>Url::toRoute(['photobook-api/upload', 'ref'=>$ref, 'id'=>$id, 'group'=>'groupname']),
                    'group_data'=>$this->photos[$group_name],
                    'photobook_id'=>$pb_id,
                    'user_id'=>$user_id,
                    'reversals'=>$this->photos[$group_name]['reversals'],
                    'change_reversals_template_url' => Url::toRoute(['photobook-api/change-reversals', 'ref'=>$ref, 'id'=>$id, 'reversals'=>'reversalsvalue', 'group'=>'groupname']),
                    'delete_template_url'=>Url::toRoute(['photobook-api/delete-group', 'ref'=>$ref, 'id'=>$id,  'group'=>'groupname']),
                    'add_group_url'=>Url::toRoute(['photobook-api/add-group', 'ref'=>$ref, 'id'=>$id]),


                ]);



                $result=['response'=>['status'=>true, 'current_group'=>$current_group]];
            }else{

                $result=['error'=>['msg'=>Yii::t('app', 'Не удалось сохранить изменения')]];
            }
        }

        return $result;


    }

    public function getPagesIndexByPhotoId($photo_id){

        if(!empty($this->data['pages'])){

            $result=[];
            foreach($this->data['pages'] as $page_index=>$page){


                foreach($page['photos'] as $key=>$photo){

                    if($photo['file_key']==$photo_id){

                        $result[]=$page_index;
                    }

                }

            }

            return $result;


        }else{


            return [];
        }
    }

    public function setPhotoProcessed($photo_id, $value){

        $this->data['processed'][$photo_id]=$value;

        return $this->save();
    }

    private function _calcPhotoZipHash(){

        $photos=$this->photos;

        $hash_str='';
        foreach($photos as $group_name=>$group){

            foreach($group['photos'] as $key=>$photo_id){

                $file_resize_path=UserUrl::photobookPhotos(false, $this->id, $this->user_id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($photo_id, UserUrl::IMAGE_ORIGINAL);

                $mtime=filemtime($file_resize_path);
                $hash_str.=$photo_id.'|'.$mtime.'||';
            }
        }


        return md5($hash_str);
    }

    public function makePhotoZip(){



        $result=[];
        $current_hash=$this->_calcPhotoZipHash();

        $zip_file_path=UserUrl::photobook(false, $this->id, $this->user_id).DIRECTORY_SEPARATOR.UserUrl::zipPhotosFile($this->id);
        $url=UserUrl::photobook(true, $this->id, $this->user_id).'/'.UserUrl::zipPhotosFile($this->id);

        if($current_hash!=$this->photos_zip_hash || !file_exists($zip_file_path)){

            $path_photos=[];
            foreach($this->photos as $group_name=>$group){
                foreach($group['photos'] as $key=>$photo_id){
                    $path_photos[]=UserUrl::photobookPhotos(false, $this->id, $this->user_id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($photo_id, UserUrl::IMAGE_ORIGINAL);
                }
            }


            if(file_exists($zip_file_path)){
                unlink($zip_file_path);
            }

            if(Utils::create_zip($path_photos, $zip_file_path, true)){


                $result=['response'=>['status'=>true, 'url'=>$url]];
            }else{

                $result=['error'=>['msg'=>Yii::t('app', 'Не удалось сосздать zip архив с фото.')]];
            }
        }else{


            $result=['response'=>['status'=>true, 'url'=>$url]];

        }

        $this->photos_zip_hash=$current_hash;
        $this->save();

        return $result;

    }



    public function deletePhoto($photo_id,  $save=false){

            $newphotos=[];

            foreach( $this->photos as $group_name=>$group){

                if(!isset($group['reversals'])){
                    $group['reversals']=1;
                }

                $newphotos[$group_name]=['photos'=>[], 'reversals'=>$group['reversals']];

                foreach( $group['photos'] as $key=>$photo){

                    if($photo_id!=$photo){
                        $newphotos[$group_name]['photos'][]=$photo;
                    }
                }

                foreach(UserUrl::$IMAGE_SIZE as $key=>$size){
                   $file_resize_path=UserUrl::photobookPhotos(false, $this->id, $this->user_id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($photo_id, $key);
                   if(file_exists($file_resize_path)){
                       unlink($file_resize_path);
                   }
                }
            }

            $this->photos=$newphotos;

            $result=['response'=>['status'=>true, 'photos'=>$this->photos,  'photo_id'=>$photo_id]];

            if($save && !$this->save()){

                $result=['error'=>['msg'=>Yii::t('app', 'Не удалось записать в базу-данных')]];
            }



        return $result;
    }






    public function deleteGroup($group){


        if(!isset($this->photos[$group])){

            $result=['error'=>['msg'=>Yii::t('app', 'Группа не найдена {param}', [ 'param'=>$group])]];
        }else{


            $photos=$this->photos[$group]['photos'];

            foreach($photos as $photo_index=>$photo){

                foreach(UserUrl::$IMAGE_SIZE as $key=>$size){

                    $file_path=UserUrl::photobookPhotos(false, $this->id, $this->user_id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($photo, $key);

                    if(file_exists($file_path)){

                        unlink($file_path);
                    }
                }
            }

            unset($this->photos[$group]);


            if($this->save()){

                $result=['response'=>['status'=>true]];

            }else{

                $result=['error'=>['msg'=>Yii::t('app', 'Не удалось сохранить изменения')]];
            }




        }

        return $result;

    }

    public function changeReversals($group, $reversals){


        if(!isset($this->photos[$group])){

            $result=['error'=>['msg'=>Yii::t('app', 'Группа не найдена {param}', [ 'param'=>$group])]];
        }else{


            $this->photos[$group]['reversals']=$reversals;



            if($this->save()){

                $result=['response'=>['status'=>true]];

            }else{

                $result=['error'=>['msg'=>Yii::t('app', 'Не удалось сохранить изменения')]];
            }




        }

        return $result;

    }


    public function changeGroupName($new_group_name, $old_group_name){


        if(isset($this->photos[$new_group_name])){

            $result=['error'=>['msg'=>Yii::t('app', 'Такая группа уже существует')]];
        }else if(!isset($this->photos[$old_group_name])){

            $result=['error'=>['msg'=>Yii::t('app', 'Группа не найдена {param} {json}',[ 'param'=>$old_group_name, 'json'=>json_encode($this->photos)])]];
        }else{


            $this->photos=Utils::change_key($old_group_name, $new_group_name, $this->photos);



            if($this->save()){

                $result=['response'=>['status'=>true]];

            }else{

                $result=['error'=>['msg'=>Yii::t('app', 'Не удалось сохранить изменения')]];
            }




        }

        return $result;

    }


    public function changeName($new_name){


       $this->name=$new_name;

       if($this->save()){

            $result=['response'=>['status'=>true]];

       }else{

            $result=['error'=>['msg'=>Yii::t('app', 'Не удалось сохранить изменения')]];
       }

       return $result;

    }


    public function delete(){


       // $this->name=$new_name;
        $photobook_dir=UserUrl::photobook(false, $this->id, $this->user_id);

        if(Photobook::deleteAll(['id'=>$this->id])){

            if(file_exists($photobook_dir)){

                Utils::rrmdir($photobook_dir);
            }

            $result=['response'=>['status'=>true]];

        }else{

            $result=['error'=>['msg'=>Yii::t('app', 'Не удалось удалить')]];
        }

        return $result;

    }


    public function changeLayout($page_index){


        $style=Style::findOne(['id'=>$this->style_id, 'delete'=>0]);



        //$style->data=json_decode($style->data, true);

        //$layouts=$style->data['layouts'];





        $templates=[];
        /*foreach($layouts as $key=>$layout){

            foreach($layout['template_ids'] as $key2=>$template_id){

                $template=Template::findOne(['id'=>$template_id]);

                $data=json_decode($template->json, true);
                $templates[$template_id]=[
                    'json'=>$data,
                    'count_placeholder'=>$template->count_placeholder,
                    'text_object'=>$template->text_object,
                    'passepartout'=>$template->passepartout,
                    'svg'=>$template->svg,
                    'pb'=>$template->pb,
                ];

                if($template->text_object){

                    $templates[$template_id]['json_text']=json_decode($template->json_text, true);
                    $templates[$template_id]['svg_text']=$template->svg_text;
                }
            }
        }*/



        $pages=$this->data['pages'];

        $layout_index=0;
        $key=$page_index;

        if(isset($pages[$key]['layout_index'])){

        $layout_index=$pages[$key]['layout_index'];
        }else{

            $layout_index=0;
        }

        $layout_index++;




        $count_photos=count($pages[$key]['photos']);


        $mapTemplates=$this->getMapTemplates();
        if(empty($mapTemplates[$count_photos][$layout_index])){

            $layout_index=0;

            $pages[$key]['layout_index']=0;
        }



        $pages[$key]['layout_index']=$layout_index;




        $pages[$key]['svg']=$this->renderSvgPage($pages[$key], $mapTemplates, $style);
        $pages[$key]['svg_thumb']=$this->renderSvgPage($pages[$key], $mapTemplates, $style, UserUrl::IMAGE_SMALL);
        $pages[$key]['json']=$this->renderJsonPage($pages[$key], $mapTemplates, $style);


        $this->data['pages']=$pages;


        if($this->save()){

            return ['response'=>['status'=>true, 'page_index'=>$page_index, 'page'=>$this->data['pages'][$page_index]]];

        }else{

            return ['error'=>['msg'=>Yii::t('app', 'Не удалось записать в базу-данных') ]];
        }

    }

    public function updatePages($style_id, $index=0){


        $style=Style::findOne(['id'=>$style_id, 'delete'=>0]);




        $style->data=json_decode($style->data, true);

        //$layouts=$style->data['layouts'];



        $mapTemplates=$this->getMapTemplates();


        if(empty($this->data['pages']))
            return [];
        $pages=$this->data['pages'];








        //$layout_index=0;
        foreach($pages as $key=>$page){



            //if(!isset($layouts[$layout_index])) $layout_index=0;

            //$layout=$layouts[$layout_index];

            //$pages[$key]['layout_index']=$layout_index;

            //$pages[$key]['layout']=$layout;

            //$pages[$key]['layout']['style_id']=$style_id;

            $pages[$key]['svg']=$this->renderSvgPage($pages[$key], $mapTemplates, $style);
            $pages[$key]['svg_thumb']=$this->renderSvgPage($pages[$key], $mapTemplates, $style, UserUrl::IMAGE_SMALL);
            $pages[$key]['json']=$this->renderJsonPage($pages[$key], $mapTemplates, $style);




          //  $layout_index++;

        }


        return $pages;

    }

    public function changeAction($page_index, $action){


        if(!empty($this->data['pages'][$page_index])){

            $this->data['pages'][$page_index]['action']=$action;

        }else{

            return ['error'=>['msg'=>Yii::t('app', 'Страница не найдена') ]];

        }

        if($this->save()){

            return ['response'=>['status'=>true, 'page_index'=>$page_index, 'page'=>$this->data['pages'][$page_index]]];

        }else{

            return ['error'=>['msg'=>Yii::t('app', 'Не удалось записать в базу-данных') ]];
        }

    }

    private function getMapTemplates(){

        $templates=Template::find()->where(['publish'=>1])->all();


        $mapTemplates=[];

        foreach($templates as $key=>$template){

            //$template->json=json_decode($template->json, true);

            $template_arr=[
                'json'=>json_decode($template->json, true),
                'count_placeholder'=>$template->count_placeholder,
                'text_object'=>$template->text_object,
                'passepartout'=>$template->passepartout,
                'svg'=>$template->svg,
                'pb'=>$template->pb,
            ];

            if(empty($mapTemplates[$template->count_placeholder])){

                $mapTemplates[$template->count_placeholder]=[];
                $mapTemplates[$template->count_placeholder][]=$template_arr;
            }else{
                $mapTemplates[$template->count_placeholder][]=$template_arr;
            }
        }

        return $mapTemplates;

    }

    public function generatePages($style_id, $index=0){


        $style=Style::findOne(['id'=>$style_id, 'delete'=>0]);




        $style->data=json_decode($style->data, true);


        $mapTemplates=$this->getMapTemplates();

        /*$templates=[];
        foreach($layouts as $key=>$layout){

            foreach($layout['template_ids'] as $key2=>$template_id){

                $template=Template::findOne(['id'=>$template_id]);

                $data=json_decode($template->json, true);
                $templates[$template_id]=[
                    'json'=>$data,
                    'count_placeholder'=>$template->count_placeholder,
                    'text_object'=>$template->text_object,
                    'passepartout'=>$template->passepartout,
                    'svg'=>$template->svg,
                    'pb'=>$template->pb,
                ];

                if($template->text_object){

                    $templates[$template_id]['json_text']=json_decode($template->json_text, true);
                    $templates[$template_id]['svg_text']=$template->svg_text;
                }
            }
        }*/


        $pages=[];


        $pages[]=[
            'photos'=>[]
        ];


        $page_index=1;
        foreach($this->photos as $group_name=>$group){

             if(isset($group['type']) && $group['type']=='text') continue;

            $photos=$group['photos'];

            $reversals=$group['reversals'];

            if(count($photos)<=0){

                continue;
                //return $pages;
            }


            //echo $reversals.'|';
            if($reversals>count($photos)){

                $reversals=count($photos);
            }




            $photo_index=0;
            $photo_on_page=intval(count($photos)/$reversals);

            if($photo_on_page>8){
                $photo_on_page=8;
            }

            $page_count=ceil(count($photos)/$photo_on_page)+1;


            //echo $page_count."|";
            for($i=1; $i<$page_count; $i++){

                for($j=0; $j<$photo_on_page; $j++){


                    if(!isset($pages[$page_index]))
                        $pages[$page_index]=[];

                    if(isset($photos[$photo_index])){

                        if(!isset($pages[$page_index]['photos']))
                        $pages[$page_index]['photos']=[];


                        $pages[$page_index]['photos'][]=[

                            'file_key'=>$photos[$photo_index],
                            'pos_dx'=>0,
                            'pos_dy'=>0,
                            'scale'=>1

                        ];

                        $photo_index++;
                    }

                }

                $page_index++;
            }



        }

        $pages[]=[
            'photos'=>[]
        ];


        if(empty($pages))
            return [];

        //$layout_index=0;
        foreach($pages as $key=>$page){


            $flyleaf=false;

            if($key==0){

                $flyleaf=true;
            }

            if($key==count($pages)-1){

                $flyleaf=true;
            }

            //if(!isset($layouts[$layout_index])) $layout_index=0;

            //$layout=$layouts[$layout_index];

            //$pages[$key]['layout']=$layout;

            //$pages[$key]['layout_index']=$layout_index;

            //$pages[$key]['layout']['style_id']=$style_id;

            $pages[$key]['svg']=$this->renderSvgPage($pages[$key], $mapTemplates, $style);
            $pages[$key]['svg_thumb']=$this->renderSvgPage($pages[$key], $mapTemplates, $style, UserUrl::IMAGE_SMALL);
            $pages[$key]['json']=$this->renderJsonPage($pages[$key], $mapTemplates, $style);
            $pages[$key]['action']='print';
            $pages[$key]['flyleaf']=$flyleaf;


            //$layout_index++;

        }


        return $pages;

    }

    public function getPageSvgWithOriginalPhotos($page){

        $style=Style::findOne(['id'=>$this->style_id, 'delete'=>0]);

        $mapTemplates=$this->getMapTemplates();

        return $this->renderSvgPage($page, $mapTemplates, $style,  UserUrl::IMAGE_ORIGINAL);
    }


    public function setImagePosAndScale($page, $place_num, $pos_x, $pos_y, $scale)
    {

        $style=Style::findOne(['id'=>$this->style_id, 'delete'=>0]);

        $mapTemplates=$this->getMapTemplates();

        $place_index=intval($place_num)-1;

        $this->data['pages'][$page]['photos'][$place_index]['pos_dx']=$pos_x;
        $this->data['pages'][$page]['photos'][$place_index]['pos_dy']=$pos_y;
        $this->data['pages'][$page]['photos'][$place_index]['scale']=$scale;


        $this->data['pages'][$page]['json']=$this->renderJsonPage($this->data['pages'][$page], $mapTemplates, $style);
        $this->data['pages'][$page]['svg']=$this->renderSvgPage($this->data['pages'][$page], $mapTemplates, $style);
        $this->data['pages'][$page]['svg_thumb']=$this->renderSvgPage($this->data['pages'][$page], $mapTemplates, $style,  UserUrl::IMAGE_SMALL);


        if($this->save()){

            return ['response'=>['status'=>true, 'page'=>$this->data['pages'][$page]]];

        }else{

            return ['error'=>['msg'=>Yii::t('app', 'Не удалось записать в базу-данных') ]];
        }


    }

    public  function deletePage($page_index){


        $new_pages=[];

        $i=0;
        foreach($this->data['pages'] as $index=>$page){

            if($index!=$page_index){
                $new_pages[$i]=$page;
                $i++;
            }
        }

        $this->data['pages']=$new_pages;

        if($this->save()){
            return ['response'=>['status'=>true]];
        }else{
            return ['error'=>['msg'=>Yii::t('app', 'Не удалось записать в базу-данных') ]];
        }
    }

    public function swapPhoto($page_index, $new_place_num,  $old_place_num){

        $style=Style::findOne(['id'=>$this->style_id, 'delete'=>0]);

        $mapTemplates=$this->getMapTemplates();

        $new_place_index=intval($new_place_num)-1;
        $old_place_index=intval($old_place_num)-1;


        $old_photo=$this->data['pages'][$page_index]['photos'][$old_place_index];

        $this->data['pages'][$page_index]['photos'][$old_place_index]=$this->data['pages'][$page_index]['photos'][$new_place_index];

        $this->data['pages'][$page_index]['photos'][$new_place_index]=$old_photo;



        $this->data['pages'][$page_index]['json']=$this->renderJsonPage($this->data['pages'][$page_index], $mapTemplates, $style);
        $this->data['pages'][$page_index]['svg']=$this->renderSvgPage($this->data['pages'][$page_index], $mapTemplates, $style);
        $this->data['pages'][$page_index]['svg_thumb']=$this->renderSvgPage($this->data['pages'][$page_index], $mapTemplates, $style, UserUrl::IMAGE_SMALL);


        if($this->save()){

            return ['response'=>['status'=>true, 'page'=>$this->data['pages'][$page_index]]];

        }else{

            return ['error'=>['msg'=>Yii::t('app', 'Не удалось записать в базу-данных') ]];
        }

    }


    public function movePage($old_page_index, $new_page_index){



       /* if($new_page_index==count($this->data['pages'])-1 || $new_page_index==count($this->data['pages'])){
            $new_page_index=$new_page_index-1;
        }*/
        if($old_page_index<$new_page_index){
            $new_page_index=$new_page_index-1;
        }



        Utils::moveElement($this->data['pages'], $old_page_index, $new_page_index);

        //if($new_page_index==count($this->data['pages']))

        if($this->save()){
            return ['response'=>['status'=>true, 'pages'=>Utils::pages_filter($this->data['pages'])]];
        }else{
            return ['error'=>['msg'=>Yii::t('app', 'Не удалось записать в базу-данных') ]];
        }
    }

    public function addNewPage($page_index, $photo_id){



        $style=Style::findOne(['id'=>$this->style_id, 'delete'=>0]);



        $mapTemplates=$this->getMapTemplates();


        $pages=[['photos'=>[
            [

                'file_key'=>$photo_id,
                'pos_dx'=>0,
                'pos_dy'=>0,
                'scale'=>1

            ]

        ]]];

        foreach($pages as $key=>$page){



            $pages[$key]['svg']=$this->renderSvgPage($pages[$key], $mapTemplates, $style);
            $pages[$key]['svg_thumb']=$this->renderSvgPage($pages[$key], $mapTemplates, $style, UserUrl::IMAGE_SMALL);
            $pages[$key]['json']=$this->renderJsonPage($pages[$key], $mapTemplates, $style);
            $pages[$key]['action']='print';
            $pages[$key]['flyleaf']=false;


        }


        Utils::array_insert($this->data['pages'],  $pages[0], intval($page_index) );

        if($this->save()){

            $backgroundUrl=UserUrl::stylePaddedPassepartout(true,$style->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($style->padded_passepartout_key, UserUrl::IMAGE_ORIGINAL);

            return ['response'=>['status'=>true, 'page_index'=>$page_index, 'pages'=>Utils::pages_filter($this->data['pages']), 'background_url'=>$backgroundUrl]];
        }else{
            return ['error'=>['msg'=>Yii::t('app', 'Не удалось записать в базу-данных') ]];
        }
    }



    public function replacePhoto($page_index, $place_num, $photo_id){

        $style=Style::findOne(['id'=>$this->style_id, 'delete'=>0]);

        $mapTemplates=$this->getMapTemplates();


        $place_index=intval($place_num)-1;


        $this->data['pages'][$page_index]['photos'][$place_index]=[
            'file_key'=>$photo_id,
            'pos_dx'=>0,
            'pos_dy'=>0,
            'scale'=>1,
            'ext'=>($this->isText($photo_id)) ? 'png' : 'jpg'
        ];


        $this->data['pages'][$page_index]['json']=$this->renderJsonPage($this->data['pages'][$page_index], $mapTemplates, $style);
        $this->data['pages'][$page_index]['svg']=$this->renderSvgPage($this->data['pages'][$page_index], $mapTemplates, $style);
        $this->data['pages'][$page_index]['svg_thumb']=$this->renderSvgPage($this->data['pages'][$page_index], $mapTemplates, $style, UserUrl::IMAGE_SMALL);


        if($this->save()){
            return ['response'=>['status'=>true, 'page'=>$this->data['pages'][$page_index]]];
        }else{
            return ['error'=>['msg'=>Yii::t('app', 'Не удалось записать в базу-данных') ]];
        }

    }


    public function addPhoto($photo_id, $group, $save=false){

        if(isset($this->photos[$group])){


            if(empty($this->photos[$group]['photos']))
                $this->photos[$group]['photos']=[];


            $file_path=UserUrl::photobookPhotos(false, $this->id, $this->user_id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($photo_id, UserUrl::IMAGE_ORIGINAL);

            $size=getimagesize($file_path);

            if( empty($this->data['sizes'])){


                $this->data['sizes']=[];
            }



            $this->data['sizes'][$photo_id]=['width'=>$size[0], 'height'=>$size[1], 'mtime'=>filemtime($file_path)];

            $this->photos[$group]['photos'][]=$photo_id;

            $current_photo=ThumbInGroup::widget([
                'photobook_id'=>$this->id,
                'photo_id'=>$photo_id,
                'user_id'=>$this->user_id
            ]);
            /*$current_photo= UploadPhotosGroup::widget([

                'group_name'=>$group,
                'change_group_name_template_url' => Url::toRoute(['photobook-api/change-group-name', 'ref'=>$ref, 'id'=>$id, 'oldgroup'=>'oldgroupname', 'newgroup'=>'newgroupname']),
                'upload_files_url'=>Url::toRoute(['photobook-api/upload', 'ref'=>$ref, 'id'=>$id, 'group'=>$group]),
                'upload_files_template_url'=>Url::toRoute(['photobook-api/upload', 'ref'=>$ref, 'id'=>$id, 'group'=>'groupname']),
                'group_data'=>$group_data,
                'photobook_id'=>$pb_id

            ]);*/
            $result=['response'=>['status'=>true, 'photos'=>$this->photos, 'current_photo'=>$current_photo, 'photo_id'=>$photo_id]];

            if($save && !$this->save()){

                $result=['error'=>['msg'=>Yii::t('app', 'Не удалось записать в базу-данных')]];
            }

        }else{

            $result=['error'=>['msg'=>Yii::t('app', 'Группа не существует')]];
        }

        return $result;
    }



    public  function addText($text){


        $group='Текст';
        if(empty($this->photos[$group])){

            $this->photos[$group]=[];
        }


        //нужно сгенерить фотку из текста


        $style=Style::findOne(['id'=>$this->style_id]);

        if(empty($style)){


            return ['error'=>['msg'=>Yii::t('app', 'Стиль для фотокниги не найден')]];
        }



        $font=Font::findOne(['id'=>$style->font_id]);


        if(empty($font)){

            return ['error'=>['msg'=>Yii::t('app', 'Шрифт для фотокниги не найден')]];
        }

        $font_path=UserUrl::font(false).DIRECTORY_SEPARATOR.UserUrl::fontFile($font->file);

        if(!file_exists($font_path)){

            return ['error'=>['msg'=>Yii::t('app', 'Файл шрифта не найден.')]];
        }


        $place_width=350;
        $place_height=350;


        $text_color='#000000'; //Ставим черный нужно брать из стиля но пока нет поля


        $image_data=Utils::makeTextImage($text, $place_width, $place_height, $text_color, $font_path, 5);



        $text_photo_id=AlphaId::id(rand(10000000000, 9999999999999));
        $text_photo_path=UserUrl::photobookPhotos(false, $this->id, $this->user_id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($text_photo_id, UserUrl::IMAGE_ORIGINAL, 'png');
        file_put_contents($text_photo_path, $image_data);



        foreach(UserUrl::$IMAGE_SIZE as $key=>$size){

            if($key!=UserUrl::IMAGE_ORIGINAL){

                $image=Yii::$app->image->load($text_photo_path);

                $type= Yii\image\drivers\Image::HEIGHT;

                if($size['width']>0 && $size['height']>0){

                    $type=Yii\image\drivers\Image::AUTO;
                }

                if($size['width']>0 && $size['height']==0){

                    $type=Yii\image\drivers\Image::WIDTH;
                }

                $file_resize_path=UserUrl::photobookPhotos(false, $this->id, $this->user_id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($text_photo_id, $key, 'png');
                $image->resize($size['width'],$size['height'],  $type);
                $image->save($file_resize_path);

                //$paths[]=$file_resize_path;
            }
        }


        if(empty($this->photos[$group]['photos'])){

            $this->photos[$group]['photos']=[];
        }

        if(empty($this->photos[$group]['texts'])){

            $this->photos[$group]['texts']=[];
        }



        $this->photos[$group]['photos'][]=$text_photo_id;

        $this->photos[$group]['texts'][$text_photo_id]=[
            'photo_id'=>$text_photo_id,
            'place_width'=>$place_width,
            'place_height'=>$place_height,
            'text_color'=>$text_color,
            'font_file'=>$font->file,
            'font_id'=>$font->id,
            'text'=>$text

        ];



        if($this->save()){


            $current_photo=ThumbInGroup::widget([
                'photobook_id'=>$this->id,
                'photo_id'=>$text_photo_id,
                'user_id'=>$this->user_id
            ]);

            return ['response'=>['status'=>true, 'photos'=>$this->photos, 'current_photo'=>$current_photo, 'photo_id'=>$text_photo_id]];

            //return ['response'=>['status'=>true, 'page'=>$this->data['pages'][$page_index]]];

        }else{

            return ['error'=>['msg'=>Yii::t('app', 'Не удалось записать в базу-данных') ]];
        }

    }


    public  function changeText($page_index, $text){




        $style=Style::findOne(['id'=>$this->style_id, 'delete'=>0]);

        $style->data=json_decode($style->data, true);

        $layouts=$style->data['layouts'];

        $templates=[];
        foreach($layouts as $key=>$layout){

            foreach($layout['template_ids'] as $key2=>$template_id){

                $template=Template::findOne(['id'=>$template_id]);

                $data=json_decode($template->json, true);

                if($template->text_object){

                    $data_text=json_decode($template->json_text, true);

                    $templates[$template_id]=[
                        'json'=>$data,
                        'json_text'=>$data_text,
                        'count_placeholder'=>$template->count_placeholder,
                        'text_object'=>$template->text_object,
                        'passepartout'=>$template->passepartout,
                        'svg'=>$template->svg,
                        'svg_text'=>$template->svg_text,
                        'pb'=>$template->pb,
                    ];

                }else{

                    $templates[$template_id]=[
                        'json'=>$data,
                        'count_placeholder'=>$template->count_placeholder,
                        'text_object'=>$template->text_object,
                        'passepartout'=>$template->passepartout,
                        'svg'=>$template->svg,
                        'pb'=>$template->pb,
                    ];
                }
            }
        }



        $count_photos=count($this->data['pages'][$page_index]['photos']);

        $template_id= $this->data['pages'][$page_index]['layout']['template_ids']['ph_count_'.$count_photos];

        if(!$templates[$template_id]['text_object']){

            return ['error'=>['msg'=>Yii::t('app', 'Макет не поддерживает текстовый блок') ]];
        }

        $page=$this->data['pages'][$page_index];


        if((!empty($page['text']) && $page['text']['text']!=$text) || empty($page['text'])){

            if(!empty($page['text']) && !empty($page['text']['file_id'])){

                $file_id=$page['text']['file_id'];
                //$file_path=UserUrl::photobookTexts(false, $this->id, $this->user_id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, UserUrl::IMAGE_ORIGINAL);

                foreach(UserUrl::$IMAGE_SIZE as $key=>$size){

                    $file_delete_path=UserUrl::photobookTexts(false, $this->id, $this->user_id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, $key, 'png');

                    if(file_exists($file_delete_path)){

                        unlink($file_delete_path);
                    }
                }
            }

            $file_id=AlphaId::id(rand(10000000000, 9999999999999));
            $file_path=UserUrl::photobookTexts(false, $this->id, $this->user_id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, UserUrl::IMAGE_ORIGINAL, 'png');
            $this->data['pages'][$page_index]['text']=['text'=>$text, 'file_id'=>$file_id];

            //$json_text=$templates[$template_id]['json_text'];



        }


        $mapTemplates=$this->getMapTemplates();



        $this->data['pages'][$page_index]['json']=$this->renderJsonPage($this->data['pages'][$page_index], $mapTemplates, $style);
        $this->data['pages'][$page_index]['svg']=$this->renderSvgPage($this->data['pages'][$page_index], $mapTemplates, $style);

        $this->data['pages'][$page_index]['svg_thumb']=$this->renderSvgPage($this->data['pages'][$page_index], $mapTemplates, $style, UserUrl::IMAGE_SMALL);




        if($this->save()){

            return ['response'=>['status'=>true, 'page'=>$this->data['pages'][$page_index]]];

        }else{

            return ['error'=>['msg'=>Yii::t('app', 'Не удалось записать в базу-данных') ]];
        }

    }

    public function isText($photo_id){


        foreach($this->photos as $group_key=>$data){

            if(!empty($data['texts']) && !empty($data['texts'][$photo_id])){


                return true;
            }


        }

        return false;
    }

    public  function addPhotoToPage($page, $photo_id){


        $style=Style::findOne(['id'=>$this->style_id, 'delete'=>0]);

        $mapTemplates=$this->getMapTemplates();

        $max_count_placeholder=0;
        foreach($mapTemplates as $count=>$templates){

            foreach($templates as $index=>$template)

            if($template['count_placeholder']>$max_count_placeholder){
                $max_count_placeholder=$template['count_placeholder'];
            }
        }

        if(count($this->data['pages'][$page]['photos'])+1>$max_count_placeholder){

            return ['error'=>['msg'=>Yii::t('app', 'Максимальное количество фото в этом шаблоне {max}', ['max'=>$max_count_placeholder]) ]];
        }



        $this->data['pages'][$page]['photos'][]=[
            'file_key'=>$photo_id,
            'pos_dx'=>0,
            'pos_dy'=>0,
            'scale'=>1,
            'ext'=>($this->isText($photo_id)) ? 'png': 'jpg'
        ];


        $this->data['pages'][$page]['json']=$this->renderJsonPage($this->data['pages'][$page], $mapTemplates, $style);
        $this->data['pages'][$page]['svg']=$this->renderSvgPage($this->data['pages'][$page], $mapTemplates, $style);
        $this->data['pages'][$page]['svg_thumb']=$this->renderSvgPage($this->data['pages'][$page], $mapTemplates, $style, UserUrl::IMAGE_SMALL);

        if($this->save()){

            return ['response'=>['status'=>true, 'page'=>$this->data['pages'][$page]]];

        }else{

            return ['error'=>['msg'=>Yii::t('app', 'Не удалось записать в базу-данных') ]];
        }

    }

    public  function deletePlaceholder($page, $place_num, $photo_id){


        $style=Style::findOne(['id'=>$this->style_id, 'delete'=>0]);




        $mapTemplates=$this->getMapTemplates();



        $new_photos=array();

        $photos=$this->data['pages'][$page]['photos'];

        if(count($photos)==1){

            return ['response'=>['status'=>true, 'page'=>$this->data['pages'][$page]]];
        }

        $place_index=intval($place_num)-1;
        $i=0;
        foreach ($photos as $index=>$photo){

            if($index!=$place_index){

                $new_photos[$i]=$photo;
                $i++;
            }

        }

        $this->data['pages'][$page]['photos']=$new_photos;


        $this->data['pages'][$page]['json']=$this->renderJsonPage($this->data['pages'][$page], $mapTemplates, $style);
        $this->data['pages'][$page]['svg']=$this->renderSvgPage($this->data['pages'][$page], $mapTemplates, $style);
        $this->data['pages'][$page]['svg_thumb']=$this->renderSvgPage($this->data['pages'][$page], $mapTemplates, $style, UserUrl::IMAGE_SMALL);




        if($this->save()){

            return ['response'=>['status'=>true, 'page'=>$this->data['pages'][$page]]];

        }else{

            return ['error'=>['msg'=>Yii::t('app', 'Не удалось записать в базу-данных') ]];
        }

    }




    public function renderJsonPage($page, $templates, $style){

        if(!isset($page['photos']))
            return [];

       // echo '<br/>';
        $count_photos=count($page['photos']);


        $layout_index=0;

        if(!empty($page['layout_index'])){


            $layout_index=$page['layout_index'];

        }





        if( empty($templates[$count_photos][$layout_index])){

            $layout_index=0;
            $page['layout_index']=0;
        }

        //$template_id=$page['layout']['template_ids']['ph_count_'.$count_photos];
        $style_id=$style->id;
        //$page['layout']['style_id'];

        if(!empty($templates[$count_photos])) {

            $json = $templates[$count_photos][$layout_index]['json'];//$templates[$template_id]['json'];
        }else{

            $json =json_decode('{"objects":[]}', true);
        }


        /*if(!empty($page['text'])){

            $json=$templates[$template_id]['json_text'];
        }*/


        $photo_index=0;

        $objects=[];
        foreach($json['objects'] as $key=>$placeholder){


            if(!empty($placeholder) && $placeholder['type']=='placeholder'){


               /* if($placeholder['object_maybe_as_text']){

                    $photo_id=$page['text']['file_id'];
                    $place_width=$placeholder['width'];
                    $place_height=$placeholder['height'];
                    $placeholder['image']=json_decode(TextPlaceholderReplacer::widget([
                        'user_id'=>$this->user_id,
                        'photobook_id'=>$this->id,
                        'photo_id'=>$photo_id,
                        'text_label'=>$page['text']['text'],
                        'place_width'=>$place_width,
                        'place_height'=>$place_height,
                        'image_size'=>UserUrl::IMAGE_MIDDLE,
                        'view'=>'json',
                        'update_img'=>false
                    ]), true);

                    $objects[count($objects)]=$placeholder;

                }else*/
                {

                    $photo_index=intval($placeholder['data_value'])-1;

                    $photo_id=$page['photos'][$photo_index]['file_key'];

                    if(!isset($page['photos'][$photo_index]['pos_dx'])){

                        $page['photos'][$photo_index]['pos_dx']=0;
                        $page['photos'][$photo_index]['pos_dy']=0;
                        $page['photos'][$photo_index]['scale']=1;
                    }

                    $pos_dx=$page['photos'][$photo_index]['pos_dx'];
                    $pos_dy=$page['photos'][$photo_index]['pos_dy'];
                    $scale=$page['photos'][$photo_index]['scale'];


                    $ext='jpg';

                    if(!empty($page['photos'][$photo_index]['ext'])){

                        $ext=$page['photos'][$photo_index]['ext'];

                    }


                    $place_width=$placeholder['width'];
                    $place_height=$placeholder['height'];

                    $placeholder['image']=json_decode(ImagePlaceholderReplacer::widget([
                        'user_id'=>$this->user_id,
                        'photobook_id'=>$this->id,
                        'photo_id'=>$photo_id,
                        'place_width'=>$place_width,
                        'place_height'=>$place_height,
                        'real_width'=> $this->data['sizes'][$photo_id]['width'],
                        'real_height'=> $this->data['sizes'][$photo_id]['height'],
                        'mtime'=> $this->data['sizes'][$photo_id]['mtime'],
                        'image_size'=>UserUrl::IMAGE_MIDDLE,
                        'scale'=>$scale,
                        'pos_dx'=>$pos_dx,
                        'pos_dy'=>$pos_dy,
                        'ext'=>$ext,
                        'view'=>'json'
                    ]), true);

                    $objects[count($objects)]=$placeholder;


                    //$photo_index++;

                }

            }

        }

        //print_r($json);

        return $objects;

    }

    public function renderSvgPage($page, $templates, $style, $size=null){

        if(!isset($page['photos']))
            return '';
        $count_photos=count($page['photos']);


        //$template_id=$page['layout']['template_ids']['ph_count_'.$count_photos];
        $style_id=$style->id;//$page['layout']['style_id'];


        $placeholder_border_color_top_left=$style->placeholder_border_color_top_left;
        $placeholder_border_color_bottom_right=$style->placeholder_border_color_bottom_right;

            /*
             *
             * public $placeholder_border_color_top_left='#000000';

    public $placeholder_border_color_bottom_right='#FFFFFF';
             */

        $layout_index=0;

        if(!empty($page['layout_index'])){


            $layout_index=$page['layout_index'];

        }





        if( empty($templates[$count_photos][$layout_index])){

            $layout_index=0;

            $page['layout_index']=0;
        }


        if(!empty($templates[$count_photos])) {
            $svg = $templates[$count_photos][$layout_index]['svg'];
        }else{
            $svg='<?xml version="1.0" encoding="UTF-8" standalone="no" ?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">'.
                 '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="700" height="250" viewBox="0 0 700 250" xml:space="preserve"><desc>Created with Fabric.js 1.4.0</desc><defs></defs>'.
                 '<g transform="translate(0 0)"></g>'.
                 '<rect x="-2" y="-125" rx="0" ry="0" width="4" height="250" style="stroke: none; stroke-width: 1; stroke-dasharray: ; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: black; opacity: 1;" transform="translate(350 125)"/>'.
                 '</svg>';
        }

        /*if(!empty($page['text'])){

            $svg=$templates[$template_id]['svg_text'];
        }*/



        $background_color='#ffffff';//$page['layout']['background_color'];
        $background_image=false;//$page['layout']['background_image'];


        $photo_index=0;

        $doc = new \DOMDOcument;
        $doc->loadxml($svg);


        //$xpath = new \DOMXpath($doc);


        //$childre=new \DOMNodeList();
/*
 *
 * <?xml version="1.0" encoding="UTF-8" standalone="no" ?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="700" height="350" viewBox="0 0 700 350" xml:space="preserve"><desc>Created with Fabric.js 1.4.0</desc><defs></defs><g transform="translate(172.5 85)" data_name="1" data_value="1" passepartout="false" object_text="false" border_left="false" border_top="false" border_right="false" border_bottom="false" border_color="#FFFFFF" border_width="3"><rect x="-172.5" y="-85" rx="0" ry="0" width="345" height="170" style="stroke: none; stroke-width: 1; stroke-dasharray: ; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: #ccc; opacity: 1;" transform="translate(0 0)"/><g transform="translate(0 0)"><text font-family="Arial" font-size="40" font-weight="normal" style="stroke: none; stroke-width: 1; stroke-dasharray: ; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: #bbb; opacity: 1;" transform="translate(-11 39)"><tspan x="0" y="-26" fill="#bbb">1</tspan></text></g><g transform=""><path d="M 0 170 L 0 0 L 345 0 L 345 170 L 0 170 z" style="stroke: #FFFFFF; stroke-width: 3; stroke-dasharray: ; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: transparent; opacity: 1; visibility: hidden;" transform="translate(-172.5 -85)" stroke-linecap="round" /></g></g><g transform="translate(83.75 265)" data_name="2" data_value="2" passepartout="false" object_text="false" border_left="false" border_top="false" border_right="false" border_bottom="false" border_color="#FFFFFF" border_width="3"><rect x="-83.75" y="-85" rx="0" ry="0" width="167.5" height="170" style="stroke: none; stroke-width: 1; stroke-dasharray: ; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: #ccc; opacity: 1;" transform="translate(0 0)"/><g transform="translate(0 0)"><text font-family="Arial" font-size="40" font-weight="normal" style="stroke: none; stroke-width: 1; stroke-dasharray: ; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: #bbb; opacity: 1;" transform="translate(-11 39)"><tspan x="0" y="-26" fill="#bbb">2</tspan></text></g><g transform=""><path d="M 0 170 L 0 0 L 167.5 0 L 167.5 170 L 0 170 z" style="stroke: #FFFFFF; stroke-width: 3; stroke-dasharray: ; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: transparent; opacity: 1; visibility: hidden;" transform="translate(-83.5 -85)" stroke-linecap="round" /></g></g><g transform="translate(261.25 265)" data_name="3" data_value="3" passepartout="false" object_text="false" border_left="false" border_top="false" border_right="false" border_bottom="false" border_color="#FFFFFF" border_width="3"><rect x="-83.75" y="-85" rx="0" ry="0" width="167.5" height="170" style="stroke: none; stroke-width: 1; stroke-dasharray: ; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: #ccc; opacity: 1;" transform="translate(0 0)"/><g transform="translate(0 0)"><text font-family="Arial" font-size="40" font-weight="normal" style="stroke: none; stroke-width: 1; stroke-dasharray: ; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: #bbb; opacity: 1;" transform="translate(-11 39)"><tspan x="0" y="-26" fill="#bbb">3</tspan></text></g><g transform=""><path d="M 0 170 L 0 0 L 167.5 0 L 167.5 170 L 0 170 z" style="stroke: #FFFFFF; stroke-width: 3; stroke-dasharray: ; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: transparent; opacity: 1; visibility: hidden;" transform="translate(-83.5 -85)" stroke-linecap="round" /></g></g><g transform="translate(438.75 85)" data_name="5" data_value="5" passepartout="false" object_text="false" border_left="false" border_top="false" border_right="false" border_bottom="false" border_color="#FFFFFF" border_width="3"><rect x="-83.75" y="-85" rx="0" ry="0" width="167.5" height="170" style="stroke: none; stroke-width: 1; stroke-dasharray: ; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: #ccc; opacity: 1;" transform="translate(0 0)"/><g transform="translate(0 0)"><text font-family="Arial" font-size="40" font-weight="normal" style="stroke: none; stroke-width: 1; stroke-dasharray: ; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: #bbb; opacity: 1;" transform="translate(-11 39)"><tspan x="0" y="-26" fill="#bbb">5</tspan></text></g><g transform=""><path d="M 0 170 L 0 0 L 167.5 0 L 167.5 170 L 0 170 z" style="stroke: #FFFFFF; stroke-width: 3; stroke-dasharray: ; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: transparent; opacity: 1; visibility: hidden;" transform="translate(-83.5 -85)" stroke-linecap="round" /></g></g><g transform="translate(527.5 265)" data_name="6" data_value="6" passepartout="false" object_text="false" border_left="false" border_top="false" border_right="false" border_bottom="false" border_color="#FFFFFF" border_width="3"><rect x="-172.5" y="-85" rx="0" ry="0" width="345" height="170" style="stroke: none; stroke-width: 1; stroke-dasharray: ; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: #ccc; opacity: 1;" transform="translate(0 0)"/><g transform="translate(0 0)"><text font-family="Arial" font-size="40" font-weight="normal" style="stroke: none; stroke-width: 1; stroke-dasharray: ; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: #bbb; opacity: 1;" transform="translate(-11 39)"><tspan x="0" y="-26" fill="#bbb">6</tspan></text></g><g transform=""><path d="M 0 170 L 0 0 L 345 0 L 345 170 L 0 170 z" style="stroke: #FFFFFF; stroke-width: 3; stroke-dasharray: ; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: transparent; opacity: 1; visibility: hidden;" transform="translate(-172.5 -85)" stroke-linecap="round" /></g></g><g transform="translate(616.25 85)" data_name="4" data_value="4" passepartout="false" object_text="false" border_left="false" border_top="false" border_right="false" border_bottom="false" border_color="#FFFFFF" border_width="3"><rect x="-83.75" y="-85" rx="0" ry="0" width="167.5" height="170" style="stroke: none; stroke-width: 1; stroke-dasharray: ; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: #ccc; opacity: 1;" transform="translate(0 0)"/><g transform="translate(0 0)"><text font-family="Arial" font-size="40" font-weight="normal" style="stroke: none; stroke-width: 1; stroke-dasharray: ; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: #bbb; opacity: 1;" transform="translate(-11 39)"><tspan x="0" y="-26" fill="#bbb">4</tspan></text></g><g transform=""><path d="M 0 170 L 0 0 L 167.5 0 L 167.5 170 L 0 170 z" style="stroke: #FFFFFF; stroke-width: 3; stroke-dasharray: ; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: transparent; opacity: 1; visibility: hidden;" transform="translate(-83.5 -85)" stroke-linecap="round" /></g></g></svg>
 */

        $defs=null;
        $defsItem=null;

        for($i=0; $i<$doc->childNodes->length; $i++){

            $childs=$doc->childNodes->item($i);



            //print_r($childs);

            if($childs->hasChildNodes()){


                for($j=0; $j<$childs->childNodes->length; $j++){


                    $item=$childs->childNodes->item($j);

                   // echo $item->tagName.' ';


                    if($item->tagName=='defs'){

                        $defs=$item;

                    }

                    if($defs){

                        $defsItem=$item;
                        $defs=null;
                    }




                    if($item->tagName=='g'){

                        for($a=0; $a<$item->attributes->length; $a++){


                            $attr=$item->attributes->item($a);

                            $data_name=$item->attributes->getNamedItem('data_name');

                            if($data_name && $data_name->textContent){

                                if($item->childNodes){

                                    for($k=0; $k<$item->childNodes->length; $k++){

                                        $element_in_ph=$item->childNodes->item($k);

                                        if($element_in_ph->tagName=='rect'){

                                            $place_width=$element_in_ph->attributes->getNamedItem('width')->textContent;
                                            $place_height=$element_in_ph->attributes->getNamedItem('height')->textContent;

                                            $node = $doc->createDocumentFragment();

                                            if($data_name->textContent!='T'){

                                                $photo_index=intval($data_name->textContent)-1;

                                                $photo_id=$page['photos'][$photo_index]['file_key'];

                                                if(!isset($page['photos'][$photo_index]['pos_dx'])){

                                                    $page['photos'][$photo_index]['pos_dx']=0;
                                                    $page['photos'][$photo_index]['pos_dy']=0;
                                                    $page['photos'][$photo_index]['scale']=1;
                                                   // $page['photos'][$photo_index]['ext']=;

                                                }

                                                $pos_dx=$page['photos'][$photo_index]['pos_dx'];
                                                $pos_dy=$page['photos'][$photo_index]['pos_dy'];
                                                $scale=$page['photos'][$photo_index]['scale'];

                                                $ext='jpg';

                                                if(!empty($page['photos'][$photo_index]['ext'])){

                                                    $ext=$page['photos'][$photo_index]['ext'];

                                                }




                                                $image_size=UserUrl::IMAGE_MIDDLE;

                                                //Нужено сделать выбор картинки на основании scale

                                                if($size){

                                                    $image_size=$size;
                                                }

                                                $photo_index++;
                                                $node->appendXML(ImagePlaceholderReplacer::widget([

                                                    'user_id'=>$this->user_id,
                                                    'photobook_id'=>$this->id,
                                                    'photo_id'=>$photo_id,
                                                    'place_width'=>$place_width,
                                                    'place_height'=>$place_height,
                                                    'real_width'=>$this->data['sizes'][$photo_id]['width'],
                                                    'real_height'=>$this->data['sizes'][$photo_id]['height'],
                                                    'mtime'=>$this->data['sizes'][$photo_id]['mtime'],
                                                    'image_size'=>$image_size,
                                                    'scale'=>$scale,
                                                    'pos_dx'=>$pos_dx,
                                                    'pos_dy'=>$pos_dy,
                                                    'ext'=>$ext,
                                                    'passpartu_left_top_border_color'=>$placeholder_border_color_top_left,
                                                    'passpartu_right_bottom_border_color'=>$placeholder_border_color_bottom_right

                                                ]));
                                                $item->replaceChild($node, $element_in_ph);

                                            }else{



                                               $photo_id=$page['text']['file_id'];

                                                $image_size=UserUrl::IMAGE_MIDDLE;

                                                //Нужено сделать выбор картинки на основании scale

                                                if($size){

                                                    $image_size=$size;
                                                }


                                                $node->appendXML(TextPlaceholderReplacer::widget([

                                                    'user_id'=>$this->user_id,
                                                    'photobook_id'=>$this->id,
                                                    'photo_id'=>$photo_id,
                                                    'text_label'=>$page['text']['text'],
                                                    'place_width'=>$place_width,
                                                    'place_height'=>$place_height,
                                                    'image_size'=>$image_size
                                                ]));
                                                $item->replaceChild($node, $element_in_ph);


                                            }

                                        }else  if($element_in_ph->tagName=='path'){


                                            $item->removeChild($element_in_ph);
                                        }

                                        if($element_in_ph->tagName=='g' && $element_in_ph->hasChildNodes()){

                                           // $element_in_ph->removeChild($element_in_ph->childNodes->item(0));

                                            try{
                                                $elem=$element_in_ph->childNodes->item(0);
                                                if($elem->tagName=='path'){

                                                   // print_r($elem);
                                                    //$element_in_ph->removeChild($elem);
                                                }else{

                                                    $element_in_ph->removeChild($elem);
                                                }
                                            }catch(\DOMException $e){


                                            }catch(\ErrorException $e){

                                            }




                                            //$element_in_ph->childNodes->item(0)->removeChild($element_in_ph->childNodes->item(0));

                                        }
                                    }

                                }


                            }


                        }




                    }





                }


                if($defsItem){

                    $node = $doc->createDocumentFragment();

                    $image_size=UserUrl::IMAGE_MIDDLE;

                    //Нужено сделать выбор картинки на основании scale

                    if($size){

                        $image_size=$size;
                    }

                    $content='';
                    if($background_image){
                        $content=ImageBackgroundReplacer::widget([
                            'style_id'=>$style_id,
                            'file_key'=>$background_image,
                            'image_size'=>$image_size

                        ]);
                    }

                    // $node->appendXML('<rect x="0" y="0" rx="0" ry="0" width="700" height="250" style="stroke: none; stroke-width: 1; stroke-dasharray: ; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: '.$background_color.'; opacity: 1;" transform="translate(0 0)"/>'.$content);

                    $node->appendXML('<rect/>'.$content);

                    $childs->insertBefore($node, $defsItem);






                }
            }

        }



        return $doc->saveXML($doc);



    }

    public function addTextGroupIfNotExists($photos){


        $text_exists=false;

        foreach($photos as $group_name=>$group){

            if(isset($group['type']) && $group['type']=='text'){


                $text_exists=true;
                break;
            }

        }

        if(!$text_exists)
        $photos['Текст']=['type'=>'text', 'photos'=>[],'texts'=>[], 'reversals'=>3];


        return $photos;
    }

    public function setStatus($status){


        if($status!=$this->status){

            $this->status=$status;

            $this->change_status_at=time();

            if($this->save()){

                return true;
            }else{

                return false;
            }


        }else{

            return true;
        }

    }


    public function save(){

        Yii::getLogger()->log('start save photobook:'.$this->id, YII_DEBUG);

        $photobook=Photobook::findOne(['id'=>$this->id]);
        if(empty($photobook)){

            $photobook=new Photobook();
            $photobook->user_id=$this->user_id;
            $photobook->name=$this->name;
            $photobook->status=$this->status;
            $photobook->data=PhotobookForm::photosEncode($this->data);
            $photobook->template=$this->template;
            $photobook->style_id=$this->style_id;
            $photobook->cover_id=$this->cover_id;
            $photobook->title_line_1=$this->title_line_1;
            $photobook->title_line_2=$this->title_line_2;
            $photobook->title_line_3=$this->title_line_3;
            $photobook->title_line_4=$this->title_line_4;
            $photobook->photos_zip_hash=$this->photos_zip_hash;

            $photobook->change_status_at=$this->change_status_at;
            $photobook->view_access_key=$this->view_access_key;

            $photobook->invoice_id=$this->invoice_id;


            $this->photos=$this->addTextGroupIfNotExists($this->photos);
            $photobook->photos=PhotobookForm::photosEncode($this->photos);

            Yii::getLogger()->log('save:', YII_DEBUG);
            if($photobook->save()){

                $this->id=$photobook->id;
                return $photobook;

            }else{

                Yii::getLogger()->log('save error', YII_DEBUG);
            }

        }else{

            $photobook->user_id=$this->user_id;
            $photobook->name=$this->name;
            $photobook->status=$this->status;
            $photobook->data=PhotobookForm::photosEncode($this->data);
            $photobook->template=$this->template;

            $this->photos=$this->addTextGroupIfNotExists($this->photos);

            $photobook->photos=PhotobookForm::photosEncode($this->photos);
            $photobook->style_id=$this->style_id;
            $photobook->cover_id=$this->cover_id;

            $photobook->title_line_1=$this->title_line_1;
            $photobook->title_line_2=$this->title_line_2;
            $photobook->title_line_3=$this->title_line_3;
            $photobook->title_line_4=$this->title_line_4;
            $photobook->photos_zip_hash=$this->photos_zip_hash;


            $photobook->change_status_at=$this->change_status_at;
            $photobook->view_access_key=$this->view_access_key;

            $photobook->invoice_id=$this->invoice_id;


            Yii::getLogger()->log('update:', YII_DEBUG);
            //$photobook->id=$this->id;



            if($photobook->update()){
                return $photobook;
            }else{


                Yii::getLogger()->log('update error:'.print_r( $photobook, true), YII_DEBUG);
            }

        }


        return null;

    }




    public static function photosEncode($photos){

        return json_encode($photos);
    }

    public static function photosDecode($photos_json){

        return json_decode($photos_json, true);

    }

}
