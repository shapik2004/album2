<?php

namespace frontend\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;

use yii\base\Widget;
use app\components\UserUrl;
use yii\helpers\Url;



class ThumbTemplateInGroup extends Widget
{

    public $style_id;

    public $template_id;

    public $background_image;

    public $background_color;
    public $count=0;
    public $group_index=0;


    public function init()
    {
        parent::init();

    }

    public function run()
    {

        $style_css='';
        $background_image='';

        if(!empty($this->background_image)){
            $background_image=UserUrl::styleBackground(true, $this->style_id).'/'.UserUrl::imageFile($this->background_image, UserUrl::IMAGE_THUMB);
        }

        $style_css='background-color: '.$this->background_color.';';

        if(!empty($background_image)){

            $style_css.='background-image:url('.$background_image.'); background-size:cover;  background-repeat: no-repeat;';
        }


        $img=Html::img(Url::toRoute(['templates/view-svg', 'id'=>$this->template_id]), ['style'=>$style_css]);

        $a=Html::a($img, Url::toRoute(['templates/view-svg', 'id'=>$this->template_id]), [
            'class'=>'thumb thumb-template thumb-template-'.$this->count,
            'data-id'=>$this->count,
            'data-templateid'=>$this->template_id,
            'data-index'=>$this->group_index
        ]);

        echo $a;
    }


}
