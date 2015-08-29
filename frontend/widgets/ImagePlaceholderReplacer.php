<?php

namespace frontend\widgets;

use common\components\Utils;
use Yii;
use yii\helpers\Html;
use yii\helpers\Json;

use yii\base\Widget;
use app\components\UserUrl;



class ImagePlaceholderReplacer extends Widget
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
    public $scale=1.0;

    public $pos_dx=0;
    public $pos_dy=0;

    public $ext='jpg';

    public $passpartu_left_top_border_color='#ffffff';
    public $passpartu_right_bottom_border_color='#000000';



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

        $img_path=UserUrl::photobookPhotos(false, $this->photobook_id, $this->user_id).'/'.UserUrl::imageFile($this->photo_id, $this->image_size, $this->ext);
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

        if ( $original_aspect >= $thumb_aspect )
        {
            // If image is wider than thumbnail (in aspect ratio sense)
            $new_height = $thumb_height;
            $new_width = $width / ($height / $thumb_height);
        }
        else
        {
            // If the thumbnail is wider than the image
            $new_width = $thumb_width;
            $new_height = $height / ($width / $thumb_width);
        }

        $scale=$this->scale;
        $image_posX=0-(($new_width*$scale) - $thumb_width) / 2;

        $image_posY=0 - (($new_height*$scale) - $thumb_height) / 2;

        $image_real_width=$new_width;
        $image_real_height=$new_height;

        $image_posX=$image_posX-$this->pos_dx;
        $image_posY=$image_posY-$this->pos_dy;



        if($image_posX>0){

            $image_posX=0;
        }

        if($image_posY>0){

            $image_posY=0;
        }

        if($image_posX<0-($image_real_width*$scale)+$thumb_width){

            $image_posX=0-($image_real_width*$scale)+$thumb_width;
        }


        if($image_posY<0-($image_real_height*$scale)+$thumb_height){

            $image_posY=0-($image_real_height*$scale)+$thumb_height;
        }

        $img_url=UserUrl::photobookPhotos(true, $this->photobook_id, $this->user_id).'/'.UserUrl::imageFile($this->photo_id, $this->image_size,  $this->ext);
        $img_path=UserUrl::photobookPhotos(false, $this->photobook_id, $this->user_id).'/'.UserUrl::imageFile($this->photo_id, $this->image_size,  $this->ext);
        $uid=rand(1,99999999999999999);


        $lastModified=filemtime($img_path);

        if($this->view=='svg'){

            $border='';


            if($this->image_size!==UserUrl::IMAGE_ORIGINAL) {

                $border = '<path stroke-width="0.5"  fill="transparent"   stroke="' . $this->passpartu_left_top_border_color . '"  d="M 0 {height} L 0 0  L {width} 0"/>
                     <path stroke-width="0.5" fill="transparent"   stroke="' . $this->passpartu_right_bottom_border_color . '"  d="M {width} 0  L {width} {height}  L 0 {height}"/>';

            }

            $content='<g xmlns:xlink="http://www.w3.org/1999/xlink"  class="placeholder"  transform="translate(-{w2}, -{h2})" width="{width}" height="{height}" clip-path="url(#clip-path-{uid})" >
                        <defs>
                            <clipPath id="clip-path-{uid}">
                                <path d="M 0 0 h {width}  v {height}  h -{width}  v -{height}  z"/>
                            </clipPath>
                        </defs>




                        <path fill="#ffffff" fill-opacity="1" clip-path="url(#clip-path-{uid})" d="M 0 0 h {width}  v {height}  h -{width}  v -{height}  z"/>

                        <g >
                            <g transform="translate({img_pos_x},{img_pos_y}) scale({scale},{scale})" width="{width}" height="{height}" pointer-events="none">
                                <image width="{image_real_width}" height="{image_real_height}" xlink:href="{img_url}"/>

                            </g>

                            '.$border.'
                        </g>



                    </g>';

            //stroke-opacity="0.5"

            $img_url=$img_url.'?v='.$lastModified;

            $mime_type="image/jpeg";

            if($this->ext=='png'){

                $mime_type="image/png";
            }

            if($this->image_size==UserUrl::IMAGE_THUMB || $this->image_size==UserUrl::IMAGE_SMALL /*|| $this->image_size==UserUrl::IMAGE_ORIGINAL*/)
            {

               // $img_path=UserUrl::photobookPhotos(false, $this->photobook_id, $this->user_id).'/'.UserUrl::imageFile($this->photo_id, UserUrl::IMAGE_SMALL);



                $imageData = base64_encode(file_get_contents($img_path));
                $img_url='data:'.$mime_type.';base64,'.$imageData;
            }else if($this->image_size==UserUrl::IMAGE_ORIGINAL){

                $imageData = base64_encode(file_get_contents($img_path));
                $img_url='data:'.$mime_type.';base64,'.$imageData;

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
                'scale'=>$scale,

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
                'image_width'=>$this->image_width,
                'image_height'=>$this->image_height,
                'image_real_width'=>$image_real_width,
                'image_real_height'=>$image_real_height,
                'first_image_real_width'=>$image_real_width,
                'first_image_real_height'=>$image_real_height,
                'scale'=>$scale,
                'img_url'=>$img_url.'?v='.$lastModified,
                'photo_id'=>$this->photo_id,
                'w2'=>($this->place_width/2),
                'h2'=>$this->place_height/2,
                'img_pos_x'=>$image_posX,
                'img_pos_y'=>$image_posY,
                'ext'=>$this->ext,
                'last_modified'=>$lastModified
            ]);
        }


    }




}


