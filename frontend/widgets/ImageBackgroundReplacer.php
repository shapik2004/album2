<?php

namespace frontend\widgets;

use common\components\Utils;
use Yii;
use yii\helpers\Html;
use yii\helpers\Json;

use yii\base\Widget;
use app\components\UserUrl;



class ImageBackgroundReplacer extends Widget
{

    //public $user_id;
    public $style_id;
    public $file_key;
    public $image_size=UserUrl::IMAGE_MIDDLE;




    public function init()
    {
        parent::init();


    }

    public function run()
    {

        $image_posX=0;
        $image_posY=0;

        $place_width=700;
        $place_height=350;


        $img_path=UserUrl::styleBackground(false, $this->style_id).'/'.UserUrl::imageFile($this->file_key, $this->image_size);
        $size=getimagesize($img_path);

        $image_real_width=$size[0];
        $image_real_height=$size[1];


        $thumb_width = $place_width;
        $thumb_height =$place_height;

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

        $image_posX=0-($new_width - $thumb_width) / 2;

        $image_posY=0 - ($new_height - $thumb_height) / 2;

        $image_real_width=$new_width;
        $image_real_height=$new_height;






        $img_url=UserUrl::styleBackground(true, $this->style_id).'/'.UserUrl::imageFile($this->file_key, $this->image_size);
        $uid=rand(1,99999999999999999);

        $content='<g xmlns:xlink="http://www.w3.org/1999/xlink"  class="placeholder"  transform="translate(0, 0)" width="{width}" height="{height}" clip-path="url(#clip-path-{uid})" >
                    <defs>
                        <clipPath id="clip-path-{uid}">
                            <path d="M 0 0 h {width}  v {height}  h -{width}  v -{height}  z"/>
                        </clipPath>
                    </defs>

                    <path fill="#999999" fill-opacity="1" clip-path="url(#clip-path-{uid})" d="M 0 0 h {width}  v {height}  h -{width}  v -{height}  z"/>

                    <g >
                        <g transform="translate({img_pos_x},{img_pos_y})" width="{width}" height="{height}" pointer-events="none">
                            <image width="{image_real_width}" height="{image_real_height}" xlink:href="{img_url}"/>
                        </g>
                    </g>

                </g>';

        //xlink


       echo Yii::t('app', $content, [
            'width'=>$place_width,
            'height'=>$place_height,
            'uid'=>$uid,

            'image_real_width'=>$image_real_width,
            'image_real_height'=>$image_real_height,
            'img_url'=>$img_url,
               'w2'=>($place_width/2),
               'h2'=>$place_height/2,
               'img_pos_x'=>$image_posX,
               'img_pos_y'=>$image_posY



           ]
        );



    }




}


