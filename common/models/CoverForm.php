<?php
namespace common\models;


use Yii;
use yii\base\Model;

use common\models\Style;
use common\models\Template;
use frontend\widgets\ThumbInGroup;
use common\components\Utils;
use app\components\UserUrl;
use app\components\AlphaId;
use yii\helpers\Url;
use frontend\widgets\UploadPhotosGroup;
use yii\web\UploadedFile;
use frontend\widgets\StyleLayoutGroup;


/**
 * Cover form
 */
class CoverForm extends Model
{
    public $id = null;

    public $name = 'Новая  обложка';

    public $material_type = "кожа";

    public $padded_cover='style_default';

    public $cover_front='style_default';

    public $cover_back='style_default';

    public $thumb='style_default';

    public $price=0.0;

    public $price_sign="+";


    public $status = Cover::STATUS_UNPUBLISHED;


    public $default=0;

    public $window_offset_x=0.0;
    public $window_offset_y=0.0;

    public $window_width=0.0;
    public $window_height=0.0;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [


            [['thumb', 'name',   'padded_cover', 'cover_front',  'cover_back', 'material_type', 'price_sign'], 'string'],
            ['name', 'filter', 'filter' => 'trim'],
            [[ 'status', 'default'], 'integer'],
            [['price', 'window_offset_x', 'window_offset_y', 'window_width', 'window_height'], 'double'],




        ];
    }







    public function loadById($id){

        $cover=Cover::findOne(['id'=>$id]);
        if(!empty($cover)){
            $this->load( $cover->toArray(), '');


            $this->id=$id;
            //$this->data=$this->photosDecode($this->data);


            return true;
        }

        return false;
    }



    public function updateField($field_name, $value){


        $this->$field_name=$value;

        if($this->$field_name=="default" && $value==1){

            Cover::updateAll(['default'=>0]);
        }


        if(!$this->save()){

            return ['error'=>['msg'=>Yii::t('app', 'Ошибка записи в базу-данных')]];
        }

        return
            [
                'response'=>[
                    'status'=>true,
                    'value'=>$value
                ]
            ];

    }




    public function updatePaddedCover(){

        //Удаляем старую подложку обложки

        foreach( UserUrl::$IMAGE_SIZE as $image_size=>$value){

            $file_path=UserUrl::coverPadded(false, $this->id).DIRECTORY_SEPARATOR.UserUrl::imageFile($this->padded_cover, $image_size, 'png');

            if(file_exists($file_path)){
                unlink($file_path);
            }
        }


        $file = UploadedFile::getInstance($this, 'photo');

        if($file){

            if($file->size!==0){


                $file_id=AlphaId::id(rand(10000000000, 9999999999999));
                $file_path=UserUrl::coverPadded(false, $this->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, UserUrl::IMAGE_ORIGINAL, 'png');
                $file->saveAs($file_path);


                foreach(UserUrl::$IMAGE_SIZE as $image_size=>$param) {

                    if($image_size==UserUrl::IMAGE_ORIGINAL)
                        continue;


                    $image=Yii::$app->image->load($file_path);

                    $type = Yii\image\drivers\Image::WIDTH;

                    $file_resize_path = UserUrl::coverPadded(false, $this->id) . DIRECTORY_SEPARATOR . UserUrl::imageFile($file_id, $image_size, 'png');
                    $image->resize($param['width'], $param['height'], $type);
                    $image->save($file_resize_path);

                }

                $this->padded_cover=$file_id;

                if(!$this->save()){

                    return ['error'=>['msg'=>Yii::t('app', 'Ошибка записи в базу-данных')]];

                }else{

                    return
                        [
                            'response'=>[
                                'status'=>true,
                                'padded_cover'=>$file_id,
                                'padded_cover_thumb_url'=>UserUrl::coverPadded(true,$this->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, UserUrl::IMAGE_THUMB, 'png')
                            ]
                        ];
                }


            }else{

                return ['error'=>['msg'=>Yii::t('app', 'Данные не получены')]];
            }

        }else{

            return ['error'=>['msg'=>Yii::t('app', 'Данные не получены')]];
        }


    }




    public function updateCoverFront(){

        //Удаляем старую подложку обложки

        foreach( UserUrl::$IMAGE_SIZE as $image_size=>$value){

            $file_path=UserUrl::coverFront(false, $this->id).DIRECTORY_SEPARATOR.UserUrl::imageFile($this->cover_front, $image_size, 'png');

            if(file_exists($file_path)){
                unlink($file_path);
            }
        }


        $file = UploadedFile::getInstance($this, 'photo');

        if($file){

            if($file->size!==0){


                $file_id=AlphaId::id(rand(10000000000, 9999999999999));
                $file_path=UserUrl::coverFront(false, $this->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, UserUrl::IMAGE_ORIGINAL, 'png');
                $file->saveAs($file_path);


                foreach(UserUrl::$IMAGE_SIZE as $image_size=>$param) {

                    if($image_size==UserUrl::IMAGE_ORIGINAL)
                        continue;


                    $image=Yii::$app->image->load($file_path);

                    $type = Yii\image\drivers\Image::WIDTH;

                    $file_resize_path = UserUrl::coverFront(false, $this->id) . DIRECTORY_SEPARATOR . UserUrl::imageFile($file_id, $image_size, 'png');
                    $image->resize($param['width'], $param['height'], $type);
                    $image->save($file_resize_path);

                }

                $this->cover_front=$file_id;

                if(!$this->save()){

                    return ['error'=>['msg'=>Yii::t('app', 'Ошибка записи в базу-данных')]];

                }else{

                    return
                        [
                            'response'=>[
                                'status'=>true,
                                'cover_front'=>$file_id,
                                'cover_front_thumb_url'=>UserUrl::coverFront(true,$this->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, UserUrl::IMAGE_THUMB, 'png')
                            ]
                        ];
                }


            }else{

                return ['error'=>['msg'=>Yii::t('app', 'Данные не получены')]];
            }

        }else{

            return ['error'=>['msg'=>Yii::t('app', 'Данные не получены')]];
        }


    }


    public function updateCoverBack(){

        //Удаляем старую подложку обложки

        foreach( UserUrl::$IMAGE_SIZE as $image_size=>$value){

            $file_path=UserUrl::coverBack(false, $this->id).DIRECTORY_SEPARATOR.UserUrl::imageFile($this->cover_back, $image_size, 'png');

            if(file_exists($file_path)){
                unlink($file_path);
            }
        }


        $file = UploadedFile::getInstance($this, 'photo');

        if($file){

            if($file->size!==0){


                $file_id=AlphaId::id(rand(10000000000, 9999999999999));
                $file_path=UserUrl::coverBack(false, $this->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, UserUrl::IMAGE_ORIGINAL, 'png');
                $file->saveAs($file_path);


                foreach(UserUrl::$IMAGE_SIZE as $image_size=>$param) {

                    if($image_size==UserUrl::IMAGE_ORIGINAL)
                        continue;


                    $image=Yii::$app->image->load($file_path);

                    $type = Yii\image\drivers\Image::WIDTH;

                    $file_resize_path = UserUrl::coverBack(false, $this->id) . DIRECTORY_SEPARATOR . UserUrl::imageFile($file_id, $image_size, 'png');
                    $image->resize($param['width'], $param['height'], $type);
                    $image->save($file_resize_path);

                }

                $this->cover_back=$file_id;

                if(!$this->save()){

                    return ['error'=>['msg'=>Yii::t('app', 'Ошибка записи в базу-данных')]];

                }else{

                    return
                        [
                            'response'=>[
                                'status'=>true,
                                'cover_back'=>$file_id,
                                'cover_back_thumb_url'=>UserUrl::coverBack(true,$this->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, UserUrl::IMAGE_THUMB, 'png')
                            ]
                        ];
                }


            }else{

                return ['error'=>['msg'=>Yii::t('app', 'Данные не получены')]];
            }

        }else{

            return ['error'=>['msg'=>Yii::t('app', 'Данные не получены')]];
        }


    }








    public function save(){



         //Yii::getLogger()->log('start save photobook:'.$this->id, YII_DEBUG);

        $cover=Cover::findOne(['id'=>$this->id]);
        if(empty($cover)){

            $cover=new Cover();


            /*
             *
             *
             *
    public $id = null;

    public $name = 'Новая  обложка';

    public $material_type = "кожа";

    public $padded_cover='style_default';

    public $cover_front='style_default';

    public $cover_back='style_default';

    public $thumb='style_default';

    public $price=0.0;

    public $price_sign="+";


    public $status = Cover::STATUS_UNPUBLISHED;


    public $default=0;

    public $window_offset_x=0.0;
    public $window_offset_y=0.0;

    public $window_width=0.0;
    public $window_height=0.0;
             */
            $cover->name=$this->name;

            $cover->material_type=$this->material_type;
            $cover->padded_cover=$this->padded_cover;

            $cover->cover_front=$this->cover_front;

            $cover->cover_back=$this->cover_back;


            $cover->thumb=$this->thumb;

            $cover->price=$this->price;

            $cover->price_sign=$this->price_sign;


            $cover->status=$this->status;

            $cover->default=$this->default;

            $cover->window_offset_x=$this->window_offset_x;

            $cover->window_offset_y=$this->window_offset_y;

            $cover->window_width=$this->window_width;

            $cover->window_height=$this->window_height;





            Yii::getLogger()->log('save:', YII_DEBUG);
            if($cover->save()){

                $this->id=$cover->id;
                return $cover;
            }else{
                Yii::getLogger()->log('save error', YII_DEBUG);
            }

        }else{

            $cover->name=$this->name;

            $cover->material_type=$this->material_type;
            $cover->padded_cover=$this->padded_cover;

            $cover->cover_front=$this->cover_front;

            $cover->cover_back=$this->cover_back;


            $cover->thumb=$this->thumb;

            $cover->price=$this->price;

            $cover->price_sign=$this->price_sign;


            $cover->status=$this->status;

            $cover->default=$this->default;

            $cover->window_offset_x=$this->window_offset_x;

            $cover->window_offset_y=$this->window_offset_y;

            $cover->window_width=$this->window_width;

            $cover->window_height=$this->window_height;


            Yii::getLogger()->log('update:', YII_DEBUG);

            if($cover->update()){
                return $cover;
            }else{


                Yii::getLogger()->log('update error:'.print_r( $cover, true), YII_DEBUG);
            }

        }


         return null;

    }


}
