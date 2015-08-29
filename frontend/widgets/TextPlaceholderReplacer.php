<?php

namespace frontend\widgets;

use common\components\Utils;
use Yii;
use yii\helpers\Html;
use yii\helpers\Json;

use yii\base\Widget;
use app\components\UserUrl;



class TextPlaceholderReplacer extends Widget
{

    public $user_id;
    public $photobook_id;
    public $photo_id;
    public $place_width;
    public $place_height;


    public $image_width;
    public $image_height;

    public $image_size=UserUrl::IMAGE_MIDDLE;
    public $view='svg';

    public $text_label='TEST';
    public $text_color='#000000';
    public $margin=5;
    public $font=null;

    public $update_img=true;


    public function init()
    {
        parent::init();


    }

    public function run()
    {

        $image_posX=0;
        $image_posY=0;
        /* $scale=2;
         $image_real_width=$this->place_width*$scale;
         $image_real_height=$this->place_height*$scale;*/

        $img_path=UserUrl::photobookTexts(false, $this->photobook_id, $this->user_id).'/'.UserUrl::imageFile($this->photo_id, $this->image_size, 'png');






        $img_url=UserUrl::photobookTexts(true, $this->photobook_id, $this->user_id).'/'.UserUrl::imageFile($this->photo_id, $this->image_size, 'png');
        $img_path=UserUrl::photobookTexts(false, $this->photobook_id, $this->user_id).'/'.UserUrl::imageFile($this->photo_id, $this->image_size, 'png');

        if(!file_exists($img_path) || $this->update_img){



               $place_width=$this->place_width;
               $place_height=$this->place_height;
              /* $resize_val=UserUrl::$IMAGE_SIZE[$this->image_size]['size'];
               $w=Utils::pxToMm(Utils::mmToPx($place_width, 300)/$resize_val, 300);
               $h=Utils::pxToMm(Utils::mmToPx($place_height, 300)/$resize_val, 300);


            echo  $w;
            echo $h;
            die();*/
               $image_data=Utils::makeTextImage($this->text_label, $place_width, $place_height, $this->text_color, $this->font, $this->margin);
               file_put_contents($img_path, $image_data);



        }


        $size=getimagesize($img_path);

        //$img_path=UserUrl::photobookPhotos(false, $this->photobook_id, $this->user_id).'/'.UserUrl::imageFile($this->photo_id, $this->image_size);

        $image_real_width=$size[0];
        $image_real_height=$size[1];


        $thumb_width = $this->place_width;
        $thumb_height = $this->place_height;

        $width = $image_real_width;
        $height =$image_real_height;

        $original_aspect = $width / $height;
        $thumb_aspect = $thumb_width / $thumb_height;

        if ($original_aspect >= $thumb_aspect)
        {

            $new_height = $thumb_height;
            $new_width = $width / ($height / $thumb_height);
        }
        else
        {
            // If the thumbnail is wider than the image
            $new_width = $thumb_width;
            $new_height = $height / ($width / $thumb_width);
        }


        $image_posX=0-(($new_width) - $thumb_width) / 2;

        $image_posY=0 - (($new_height) - $thumb_height) / 2;

        $image_real_width=$new_width;
        $image_real_height=$new_height;


        $uid=rand(1,99999999999999999);

        $lastModified=filemtime($img_path);

        if($this->view=='svg'){
// <path fill="#999999" fill-opacity="1" clip-path="url(#clip-path-{uid})" d="M 0 0 h {width}  v {height}  h -{width}  v -{height}  z"/>
            $content='<g xmlns:xlink="http://www.w3.org/1999/xlink"  class="placeholder text"  transform="translate(-{w2}, -{h2})" width="{width}" height="{height}" clip-path="url(#clip-path-{uid})" >
                        <defs>
                            <clipPath id="clip-path-{uid}">
                                <path d="M 0 0 h {width}  v {height}  h -{width}  v -{height}  z"/>
                            </clipPath>
                        </defs>



                        <g >
                            <g transform="translate({img_pos_x},{img_pos_y}) scale(1,1)" width="{width}" height="{height}" pointer-events="none">
                                <image width="{image_real_width}" height="{image_real_height}" xlink:href="{img_url}"/>
                            </g>
                        </g>

                    </g>';


            $img_url=$img_url.'?v='.$lastModified;

            if($this->image_size==UserUrl::IMAGE_THUMB || $this->image_size==UserUrl::IMAGE_SMALL /*|| $this->image_size==UserUrl::IMAGE_ORIGINAL*/)
            {

                // $img_path=UserUrl::photobookPhotos(false, $this->photobook_id, $this->user_id).'/'.UserUrl::imageFile($this->photo_id, UserUrl::IMAGE_SMALL);
                $imageData = base64_encode(file_get_contents($img_path));
                $img_url='data:image/jpeg'.';base64,'.$imageData;
            }else if($this->image_size==UserUrl::IMAGE_ORIGINAL){

                $imageData = base64_encode(file_get_contents($img_path));
                $img_url='data:image/jpeg'.';base64,'.$imageData;

                //$img_url='http://'.$_SERVER['HTTP_HOST'].UserUrl::photobookPhotos(true, $this->photobook_id, $this->user_id).'/'.UserUrl::imageFile($this->photo_id, $this->image_size);;
            }


            echo Yii::t('app', $content, [
                    'width'=>$this->place_width,
                    'height'=>$this->place_height,
                    'uid'=>$uid,

                    'image_width'=>$this->image_width,
                    'image_height'=>$this->image_height,
                    'image_real_width'=>$image_real_width,
                    'image_real_height'=>$image_real_height,
                    'first_image_real_width'=>$image_real_width,
                    'first_image_real_height'=>$image_real_height,
                    'img_url'=>$img_url,
                    'w2'=>($this->place_width/2),
                    'h2'=>$this->place_height/2,
                    'img_pos_x'=>$image_posX,
                    'img_pos_y'=>$image_posY



                ]
            );

        }else if($this->view=='json'){

            echo json_encode([
                'width'=>$this->place_width,
                'height'=>$this->place_height,
                'uid'=>$uid,
                'object_text'=>true,
                'image_width'=>$this->image_width,
                'image_height'=>$this->image_height,
                'image_real_width'=>$image_real_width,
                'image_real_height'=>$image_real_height,
                'first_image_real_width'=>$image_real_width,
                'first_image_real_height'=>$image_real_height,
                'img_url'=>$img_url.'?v='.$lastModified,
                'photo_id'=>$this->photo_id,
                'w2'=>($this->place_width/2),
                'h2'=>$this->place_height/2,
                'img_pos_x'=>$image_posX,
                'img_pos_y'=>$image_posY,
                'last_modified'=>$lastModified
            ]);
        }


    }




}


