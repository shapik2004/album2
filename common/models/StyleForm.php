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
 * Photobook form
 */
class StyleForm extends Model
{
    public $id = null;

    public $name = 'Новая фотокнига';

    public $thumb_key='style_default';

    public $data ='';

    public $weight=1;

    public $status = Photobook::STATUS_NEW;

    public $delete=0;

    //public $padded_passepartout_key='style_default';

    public $text_for_icon='';

    public $placeholder_border_color_top_left='#000000';

    public $placeholder_border_color_bottom_right='#FFFFFF';

    public $padded_passepartout_key='style_default';

    public $padded_cover_key='style_default';

    public $max_spread;

    public $price_spread;

    public $font_id;

    public  $cover_front_key='style_default';

    public  $cover_back_key='style_default';


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [


            [['thumb_key', 'name', 'data',  'text_for_icon', 'placeholder_border_color_top_left', 'placeholder_border_color_bottom_right', 'padded_passepartout_key', 'padded_cover_key', 'cover_front_key',  'cover_back_key'], 'string'],
            ['name', 'filter', 'filter' => 'trim'],
            [['weight', 'status', 'font_id', 'max_spread'], 'integer'],
            [['price_spread'], 'double']




        ];
    }







    public function loadById($id){

        $style=Style::findOne(['id'=>$id]);
        if(!empty($style)){
            $this->load( $style->toArray(), '');


            $this->id=$id;
            $this->data=$this->photosDecode($this->data);


            return true;
        }

        return false;
    }

    public static function GenerateRandomLayout($num=1){

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

    //public function

    public function addGroup($group_name='', $after_group){


        $group_index=count($this->data['layouts']);

        $group_data=StyleForm::GenerateRandomLayout($group_index);

        if(!empty($group_name))
        $group_data['label']=$group_name;

        $i=0;
        $newlayouts=[];
        foreach($this->data['layouts'] as $key=>$group){

            $newlayouts[$i]=$group;
            $i++;
            if($key==$after_group){
                $newlayouts[$i]=$group_data;
                $i++;
            }
        }


        $this->data['layouts']=$newlayouts;



        if($this->save()){

               /* $current_group= StyleLayoutGroup::widget([

                    'group_name'=>$group_data['label'],
                    'change_group_name_template_url' => Url::toRoute(['styles-api/change-group-name', 'id'=>$this->id, 'group_index'=>$group_index, 'newgroup'=>'newgroupname']),
                    'upload_files_url'=>Url::toRoute(['styles-api/upload-background', 'id'=>$this->id, 'group_index'=>$group_index]),
                    'upload_files_template_url'=>Url::toRoute(['styles-api/upload-background', 'id'=>$this->id, 'group_index'=>'groupindex']),
                    'group_data'=>$group_data,
                    'style_id'=>$this->id,
                    'delete_url'=>Url::toRoute(['styles-api/delete-group', 'id'=>$this->id,  'group_index'=>$group_index]),
                    'change_background_color_url'=>Url::toRoute(['styles-api/change-group-background-color', 'id'=>$this->id,  'group_index'=>$group_index, 'color'=>'newcolor']),
                    'background_color'=>$group_data['background_color'],
                    'background_image'=>$group_data['background_image'],
                    'add_group_url'=>Url::toRoute(['styles-api/add-group',  'id'=>$this->id, 'after_group'=>$group_index])

                ]);*/

            $groups=[];
            $i=0;
            foreach($newlayouts as $key=>$group_data){

                    $i=$key;

                    $groups[$i]= StyleLayoutGroup::widget([

                        'group_name'=>$group_data['label'],
                        'change_group_name_template_url' => Url::toRoute(['styles-api/change-group-name', 'id'=>$this->id, 'group_index'=>$i, 'newgroup'=>'newgroupname']),
                        'upload_files_url'=>Url::toRoute(['styles-api/upload-background', 'id'=>$this->id, 'group_index'=>$i]),
                        'upload_files_template_url'=>Url::toRoute(['styles-api/upload-background', 'id'=>$this->id, 'group_index'=>'groupindex']),
                        'group_data'=>$group_data,
                        'style_id'=>$this->id,
                        'delete_url'=>Url::toRoute(['styles-api/delete-group', 'id'=>$this->id,  'group_index'=>$i]),
                        'change_background_color_url'=>Url::toRoute(['styles-api/change-group-background-color', 'id'=>$this->id,  'group_index'=>$i, 'color'=>'newcolor']),
                        'background_color'=>$group_data['background_color'],
                        'background_image'=>$group_data['background_image'],
                        'add_group_url'=>Url::toRoute(['styles-api/add-group',  'id'=>$this->id, 'after_group'=>$i]),
                        'group_index'=>$i

                    ]);

                    $i++;

            }

            $result=['response'=>['status'=>true, 'groups'=>$groups]];
        }else{

            $result=['error'=>['msg'=>Yii::t('app', 'Не удалось сохранить изменения')]];
        }


        return $result;


    }

    public function updateField($field_name, $value){


        $this->$field_name=$value;

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

    public function updatePaddedPassepartout(){

        //Удаляем старую подложку паспарту

        foreach( UserUrl::$IMAGE_SIZE as $image_size=>$value){

            $file_path=UserUrl::stylePaddedPassepartout(false, $this->id).DIRECTORY_SEPARATOR.UserUrl::imageFile($this->padded_passepartout_key, $image_size);

            if(file_exists($file_path)){
                unlink($file_path);
            }
        }


        $file = UploadedFile::getInstance($this, 'photo');

        if($file){

            if($file->size!==0){


                $file_id=AlphaId::id(rand(10000000000, 9999999999999));
                $file_path=UserUrl::stylePaddedPassepartout(false, $this->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, UserUrl::IMAGE_ORIGINAL);
                $file->saveAs($file_path);


                foreach(UserUrl::$IMAGE_SIZE as $image_size=>$param) {

                    if($image_size==UserUrl::IMAGE_ORIGINAL)
                        continue;


                    $image=Yii::$app->image->load($file_path);

                    $type = Yii\image\drivers\Image::WIDTH;

                    $file_resize_path = UserUrl::stylePaddedPassepartout(false, $this->id) . DIRECTORY_SEPARATOR . UserUrl::imageFile($file_id, $image_size);
                    $image->resize($param['width'], $param['height'], $type);
                    $image->save($file_resize_path);

                }

                $this->padded_passepartout_key=$file_id;

                if(!$this->save()){

                    return ['error'=>['msg'=>Yii::t('app', 'Ошибка записи в базу-данных')]];

                }else{

                    return
                        [
                            'response'=>[
                                'status'=>true,
                                'padded_passepartout_key'=>$file_id,
                                'padded_passepartout_thumb_url'=>UserUrl::stylePaddedPassepartout(true,$this->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, UserUrl::IMAGE_THUMB)
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



    public function updatePaddedCover(){

        //Удаляем старую подложку обложки

        foreach( UserUrl::$IMAGE_SIZE as $image_size=>$value){

            $file_path=UserUrl::stylePaddedCover(false, $this->id).DIRECTORY_SEPARATOR.UserUrl::imageFile($this->padded_cover_key, $image_size, 'png');

            if(file_exists($file_path)){
                unlink($file_path);
            }
        }


        $file = UploadedFile::getInstance($this, 'photo');

        if($file){

            if($file->size!==0){


                $file_id=AlphaId::id(rand(10000000000, 9999999999999));
                $file_path=UserUrl::stylePaddedCover(false, $this->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, UserUrl::IMAGE_ORIGINAL, 'png');
                $file->saveAs($file_path);


                foreach(UserUrl::$IMAGE_SIZE as $image_size=>$param) {

                    if($image_size==UserUrl::IMAGE_ORIGINAL)
                        continue;


                    $image=Yii::$app->image->load($file_path);

                    $type = Yii\image\drivers\Image::WIDTH;

                    $file_resize_path = UserUrl::stylePaddedCover(false, $this->id) . DIRECTORY_SEPARATOR . UserUrl::imageFile($file_id, $image_size, 'png');
                    $image->resize($param['width'], $param['height'], $type);
                    $image->save($file_resize_path);

                }

                $this->padded_cover_key=$file_id;

                if(!$this->save()){

                    return ['error'=>['msg'=>Yii::t('app', 'Ошибка записи в базу-данных')]];

                }else{

                    return
                        [
                            'response'=>[
                                'status'=>true,
                                'padded_cover_key'=>$file_id,
                                'padded_cover_thumb_url'=>UserUrl::stylePaddedCover(true,$this->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, UserUrl::IMAGE_THUMB, 'png')
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

            $file_path=UserUrl::styleCoverFront(false, $this->id).DIRECTORY_SEPARATOR.UserUrl::imageFile($this->cover_front_key, $image_size, 'png');

            if(file_exists($file_path)){
                unlink($file_path);
            }
        }


        $file = UploadedFile::getInstance($this, 'photo');

        if($file){

            if($file->size!==0){


                $file_id=AlphaId::id(rand(10000000000, 9999999999999));
                $file_path=UserUrl::styleCoverFront(false, $this->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, UserUrl::IMAGE_ORIGINAL, 'png');
                $file->saveAs($file_path);


                foreach(UserUrl::$IMAGE_SIZE as $image_size=>$param) {

                    if($image_size==UserUrl::IMAGE_ORIGINAL)
                        continue;


                    $image=Yii::$app->image->load($file_path);

                    $type = Yii\image\drivers\Image::WIDTH;

                    $file_resize_path = UserUrl::styleCoverFront(false, $this->id) . DIRECTORY_SEPARATOR . UserUrl::imageFile($file_id, $image_size, 'png');
                    $image->resize($param['width'], $param['height'], $type);
                    $image->save($file_resize_path);

                }

                $this->cover_front_key=$file_id;

                if(!$this->save()){

                    return ['error'=>['msg'=>Yii::t('app', 'Ошибка записи в базу-данных')]];

                }else{

                    return
                        [
                            'response'=>[
                                'status'=>true,
                                'cover_front_key'=>$file_id,
                                'cover_front_thumb_url'=>UserUrl::styleCoverFront(true,$this->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, UserUrl::IMAGE_THUMB, 'png')
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

            $file_path=UserUrl::styleCoverBack(false, $this->id).DIRECTORY_SEPARATOR.UserUrl::imageFile($this->cover_back_key, $image_size, 'png');

            if(file_exists($file_path)){
                unlink($file_path);
            }
        }


        $file = UploadedFile::getInstance($this, 'photo');

        if($file){

            if($file->size!==0){


                $file_id=AlphaId::id(rand(10000000000, 9999999999999));
                $file_path=UserUrl::styleCoverBack(false, $this->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, UserUrl::IMAGE_ORIGINAL, 'png');
                $file->saveAs($file_path);


                foreach(UserUrl::$IMAGE_SIZE as $image_size=>$param) {

                    if($image_size==UserUrl::IMAGE_ORIGINAL)
                        continue;


                    $image=Yii::$app->image->load($file_path);

                    $type = Yii\image\drivers\Image::WIDTH;

                    $file_resize_path = UserUrl::styleCoverBack(false, $this->id) . DIRECTORY_SEPARATOR . UserUrl::imageFile($file_id, $image_size, 'png');
                    $image->resize($param['width'], $param['height'], $type);
                    $image->save($file_resize_path);

                }

                $this->cover_back_key=$file_id;

                if(!$this->save()){

                    return ['error'=>['msg'=>Yii::t('app', 'Ошибка записи в базу-данных')]];

                }else{

                    return
                        [
                            'response'=>[
                                'status'=>true,
                                'cover_back_key'=>$file_id,
                                'cover_back_thumb_url'=>UserUrl::styleCoverBack(true,$this->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, UserUrl::IMAGE_THUMB, 'png')
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





    public function updateThumbImage(){


            if($this->thumb_key!=='style_default'){

                //Удаляем старый стиль
                $file_thumb_path=UserUrl::styleThumb(false, $this->id).DIRECTORY_SEPARATOR. UserUrl::imageFile($this->thumb_key, UserUrl::IMAGE_THUMB);

                if(file_exists($file_thumb_path)){
                    unlink($file_thumb_path);
                }


                $file_orig_path=UserUrl::styleThumb(false, $this->id).DIRECTORY_SEPARATOR. UserUrl::imageFile($this->thumb_key, UserUrl::IMAGE_ORIGINAL);

                if(file_exists($file_orig_path)){
                    unlink($file_orig_path);
                }
            }



            $file = UploadedFile::getInstance($this, 'photo');

            if($file){

                if($file->size!==0){



                    $file_id=AlphaId::id(rand(10000000000, 9999999999999));
                    $file_path=UserUrl::styleThumb(false, $this->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, UserUrl::IMAGE_ORIGINAL);
                    $file->saveAs($file_path);


                    $image=Yii::$app->image->load($file_path);

                    $type=Yii\image\drivers\Image::NONE;
                    $file_thumb_path=UserUrl::styleThumb(false, $this->id).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, UserUrl::IMAGE_THUMB);
                    $image->resize(70,70,  $type);
                    $image->save($file_thumb_path);


                    $this->thumb_key=$file_id;

                    if(!$this->save()){

                        $result=['error'=>['msg'=>Yii::t('app', 'Ошибка записи в базу-данных')]];

                    }else{

                        $result=
                            [
                                'response'=>[
                                    'status'=>true,
                                    'thumb_key'=>$file_id,
                                    'thumb_url'=>UserUrl::styleThumb(true,$this->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, UserUrl::IMAGE_THUMB)
                                ]
                            ];
                    }


                }else{

                    $result=['error'=>['msg'=>Yii::t('app', 'Данные не получены')]];
                }

            }else{

                $result=['error'=>['msg'=>Yii::t('app', 'Данные не получены')]];
            }





        return $result;
    }


    public function updateBackgroundImage($group_index, $save=false){

        if(isset($this->data['layouts'][$group_index])){


            $group=$this->data['layouts'][$group_index];
            //Если есть старый фон удаляем его
            if(!empty($group['background_image'])){

                $file_id=$group['background_image'];
                $file_path=UserUrl::styleBackground(false, $this->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, UserUrl::IMAGE_ORIGINAL);

                if(file_exists($file_path)){
                    unlink($file_path);
                }

                foreach(UserUrl::$IMAGE_SIZE as $key=>$size){

                    if($key!=UserUrl::IMAGE_ORIGINAL){

                        $file_resize_path=UserUrl::styleBackground(false, $this->id).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, $key);

                        if(file_exists($file_resize_path)){
                            unlink($file_resize_path);
                        }
                    }
                }
            }



            $file = UploadedFile::getInstance($this, 'photo');

            if($file){

                if($file->size!==0){



                    $file_id=AlphaId::id(rand(10000000000, 9999999999999));
                    $file_path=UserUrl::styleBackground(false, $this->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, UserUrl::IMAGE_ORIGINAL);
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

                            $file_resize_path=UserUrl::styleBackground(false, $this->id).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, $key);
                            $image->resize($size['width'],$size['height'],  $type);
                            $image->save($file_resize_path);

                            $paths[]=$file_resize_path;
                        }
                    }


                    $group['background_image']=$file_id;

                    $this->data['layouts'][$group_index]=$group;

                    if(!$this->save()){

                        //Удаляем все файлы если не получилось сохранить
                        if(!empty($result['error'])){

                            foreach($paths as $path){

                                if(file_exists($path))
                                    unlink($path);
                            }
                        }

                        $result=['error'=>['msg'=>Yii::t('app', 'Ошибка записи в базу-данных')]];

                    }else{

                        $result=
                            [
                                'response'=>['status'=>true,
                                'background_image'=>UserUrl::styleBackground(true,$this->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, UserUrl::IMAGE_THUMB)]
                            ];
                    }


                }else{

                    $result=['error'=>['msg'=>Yii::t('app', 'Данные не получены')]];
                }

            }else{

                $result=['error'=>['msg'=>Yii::t('app', 'Данные не получены')]];
            }






        }else{

            $result=['error'=>['msg'=>Yii::t('app', 'Группа не существует')]];
        }

        return $result;
    }


    public function setTemplateId($group_index, $ph_count, $template_id){


        if(!isset($this->data['layouts'][$group_index])){

            $result=['error'=>['msg'=>Yii::t('app', 'Группа не найдена {param}', [ 'param'=>$group_index])]];
        }else{


            $this->data['layouts'][$group_index]['template_ids']['ph_count_'.$ph_count]=$template_id;

            if($this->save()){

                $src=Url::toRoute(['templates/view-svg', 'id'=>$template_id]);
                $result=['response'=>['status'=>true, 'group_index'=>$group_index, 'ph_count'=>$ph_count, 'template_id'=>$template_id, 'src'=>$src]];

            }else{

                $result=['error'=>['msg'=>Yii::t('app', 'Не удалось сохранить изменения')]];
            }


        }

        return $result;

    }




    public function deleteGroup($group_index){


        if(!isset($this->data['layouts'][$group_index])){

            $result=['error'=>['msg'=>Yii::t('app', 'Группа не найдена {param}', [ 'param'=>$group_index])]];
        }else{


            if(count($this->data['layouts'])==1){

                $result=['error'=>['msg'=>Yii::t('app', 'Для стиля должна быть определена минимум одна группа макетов.')]];


            }else{




                $background_image=$this->data['layouts'][$group_index]['background_image'];



                foreach(UserUrl::$IMAGE_SIZE as $key=>$size){

                    $file_path=UserUrl::styleBackground(false, $this->id).DIRECTORY_SEPARATOR. UserUrl::imageFile($background_image, $key);

                    if(file_exists($file_path)){

                        unlink($file_path);
                    }
                }


                $newlayouts=[];
                $groups=[];

                $i=0;
                foreach($this->data['layouts'] as $key=>$group_data){

                    if($key!=$group_index){
                        $newlayouts[$i]=$group_data;

                        $groups[$i]= StyleLayoutGroup::widget([

                            'group_name'=>$group_data['label'],
                            'change_group_name_template_url' => Url::toRoute(['styles-api/change-group-name', 'id'=>$this->id, 'group_index'=>$i, 'newgroup'=>'newgroupname']),
                            'upload_files_url'=>Url::toRoute(['styles-api/upload-background', 'id'=>$this->id, 'group_index'=>$i]),
                            'upload_files_template_url'=>Url::toRoute(['styles-api/upload-background', 'id'=>$this->id, 'group_index'=>'groupindex']),
                            'group_data'=>$group_data,
                            'style_id'=>$this->id,
                            'delete_url'=>Url::toRoute(['styles-api/delete-group', 'id'=>$this->id,  'group_index'=>$i]),
                            'change_background_color_url'=>Url::toRoute(['styles-api/change-group-background-color', 'id'=>$this->id,  'group_index'=>$i, 'color'=>'newcolor']),
                            'background_color'=>$group_data['background_color'],
                            'background_image'=>$group_data['background_image'],
                            'add_group_url'=>Url::toRoute(['styles-api/add-group',  'id'=>$this->id, 'after_group'=>$i]),
                            'group_index'=>$i

                        ]);

                        $i++;
                    }
                }

                $this->data['layouts']=$newlayouts;

                if($this->save()){

                    $result=['response'=>['status'=>true, 'groups'=>$groups]];

                }else{

                    $result=['error'=>['msg'=>Yii::t('app', 'Не удалось сохранить изменения')]];
                }


            }


        }

        return $result;

    }

    public function changeGroupBackgroundColor($group_index, $color){


        if(!isset($this->data['layouts'][$group_index])){

            $result=['error'=>['msg'=>Yii::t('app', 'Группа не найдена {param}', [ 'param'=>$group_index])]];
        }else{


            $this->data['layouts'][$group_index]['background_color']=$color;

            $group=$this->data['layouts'][$group_index];
            //Если есть старый фон удаляем его
            if(!empty($group['background_image'])){

                $file_id=$group['background_image'];
                $file_path=UserUrl::styleBackground(false, $this->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, UserUrl::IMAGE_ORIGINAL);

                if(file_exists($file_path)){
                    unlink($file_path);
                }

                foreach(UserUrl::$IMAGE_SIZE as $key=>$size){

                    if($key!=UserUrl::IMAGE_ORIGINAL){

                        $file_resize_path=UserUrl::styleBackground(false, $this->id).DIRECTORY_SEPARATOR. UserUrl::imageFile($file_id, $key);

                        if(file_exists($file_resize_path)){
                            unlink($file_resize_path);
                        }
                    }
                }
            }

            $this->data['layouts'][$group_index]['background_image']='';


            if($this->save()){

                $result=['response'=>['status'=>true, 'background_color'=>$color]];

            }else{

                $result=['error'=>['msg'=>Yii::t('app', 'Не удалось сохранить изменения')]];
            }




        }

        return $result;

    }


    public function changeGroupName($group_index, $group_name){


        if(!isset($this->data['layouts'][$group_index])){

            $result=['error'=>['msg'=>Yii::t('app', 'Группа не найдена {param} {json}',[ 'param'=>$group_index])]];
        }else{


            $this->data['layouts'][$group_index]['label']=$group_name;


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


        $this->delete=1;

        if($this->save()){

           return true;

        }else{

            return false;
        }


    }


    public function save(){



         //Yii::getLogger()->log('start save photobook:'.$this->id, YII_DEBUG);

        $style=Style::findOne(['id'=>$this->id]);
        if(empty($style)){

            $style=new Style();

            $style->name=$this->name;
            $style->status=$this->status;
            $style->data=$this->photosEncode($this->data);
            $style->weight=$this->weight;
            $style->thumb_key=$this->thumb_key;
            $style->delete=$this->delete;

            /*
             *
             *  public $text_for_icon='';

    public $placeholder_border_color_top_left='#000000';

    public $placeholder_border_color_bottom_right='#FFFFFF';

    public $padded_passepartout_key='style_default';

    public $padded_cover_key='style_default';

             */
            $style->text_for_icon=$this->text_for_icon;

            $style->placeholder_border_color_top_left=$this->placeholder_border_color_top_left;

            $style->placeholder_border_color_bottom_right=$this->placeholder_border_color_bottom_right;

            $style->padded_passepartout_key=$this->padded_passepartout_key;

            $style->padded_cover_key=$this->padded_cover_key;

            $style->max_spread=$this->max_spread;


            $style->font_id=$this->font_id;

            $style->cover_back_key=$this->cover_back_key;

            $style->cover_front_key=$this->cover_front_key;

            $style->price_spread=$this->price_spread;





            Yii::getLogger()->log('save:', YII_DEBUG);
            if($style->save()){

                $this->id=$style->id;
                return $style;
            }else{
                Yii::getLogger()->log('save error', YII_DEBUG);
            }

        }else{

            $style->name=$this->name;
            $style->status=$this->status;
            $style->data=$this->photosEncode($this->data);
            $style->weight=$this->weight;
            $style->thumb_key=$this->thumb_key;
            $style->delete=$this->delete;

            $style->text_for_icon=$this->text_for_icon;

            $style->placeholder_border_color_top_left=$this->placeholder_border_color_top_left;

            $style->placeholder_border_color_bottom_right=$this->placeholder_border_color_bottom_right;

            $style->padded_passepartout_key=$this->padded_passepartout_key;

            $style->padded_cover_key=$this->padded_cover_key;

            $style->max_spread=$this->max_spread;

            $style->font_id=$this->font_id;

            $style->cover_back_key=$this->cover_back_key;

            $style->cover_front_key=$this->cover_front_key;

            $style->price_spread=$this->price_spread;


            Yii::getLogger()->log('update:', YII_DEBUG);

            if($style->update()){
                return $style;
            }else{


                Yii::getLogger()->log('update error:'.print_r( $style, true), YII_DEBUG);
            }

        }


         return null;

    }

    private function photosEncode($photos){

        return json_encode($photos);
    }

    private function photosDecode($photos_json){

        return json_decode($photos_json, true);

    }

}
