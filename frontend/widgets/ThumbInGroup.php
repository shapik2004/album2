<?php

namespace frontend\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;

use yii\base\Widget;
use app\components\UserUrl;



class ThumbInGroup extends Widget
{

    public $photobook_id;
    public $user_id;
    public $photo_id;


    public function init()
    {
        parent::init();


    }

    public function run()
    {


        $img=Html::img(UserUrl::photobookPhotos(true, $this->photobook_id, $this->user_id).'/'.UserUrl::imageFile($this->photo_id, UserUrl::IMAGE_THUMB), ['data-id'=>$this->photo_id]);

        $a=Html::a($img, UserUrl::photobookPhotos(true, $this->photobook_id, $this->user_id).'/'.UserUrl::imageFile($this->photo_id, UserUrl::IMAGE_MIDDLE), ['class'=>'thumb thumb-'.$this->photo_id, 'data-id'=>$this->photo_id, 'data-gallery'=>'']);

        echo $a;
    }


}
