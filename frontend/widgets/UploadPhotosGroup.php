<?php

namespace frontend\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;

use yii\base\Widget;
use app\components\UserUrl;
use frontend\widgets\ThumbInGroup;



class UploadPhotosGroup extends Widget
{


    public $group_name = 'Тест1';

    public $change_group_name_template_url = '';

    public $upload_files_url='';

    public $upload_files_template_url='';

    public $group_data;

    public $photobook_id;

    public $user_id;

    public  $reversals=3;

    public $change_reversals_template_url = '';

    public $delete_template_url = '';

    public $add_group_url='';
    public function init()
    {
        parent::init();


    }

    public function run()
    {


        /*
         *
         *   <div class="photo-group">

                            <div class="editable" data-url="/photobook-api/change-group-name?ref=gLA9o&amp;id=gLAAE&amp;oldgroup=oldgroupname&amp;newgroup=newgroupname">Дорога</div>


                            <div class="row">
                                <div class="col-xs-2">
                                    <div class="reversals">
                                        <label>Количество разворотов: </label>
                                        <input type="text" class="spinner" name="reversals" value="3" data-min="1" data-max="20" data-interval="1" data-url="/photobook-api/change-reversals?ref=gLA9o&amp;id=gLAAE&amp;reversals=reversalsvalue&amp;group=groupname">
                                    </div>

                                    <form action="/photobook-api/upload?ref=gLA9o&amp;id=gLAAE&amp;group=%D0%94%D0%BE%D1%80%D0%BE%D0%B3%D0%B0" enctype="multipart/form-data">

                                         <span class="btn btn-default button-2-line fileinput-button ladda-button" data-style="zoom-in" data-spinner-color="#993149">
                                            <!--<i class="glyphicon glyphicon-plus"></i>-->
                                            <div class="button-col button-icon ">
                                                <i class="glyphicons picture"></i>
                                            </div>
                                            <div class="button-col">
                                                <?php echo Yii::t('app', 'Добавить<br/>фото в группу'); ?>
                                            </div>

                                            <input type="file" class="fileupload" name="PhotobookForm[photo]" value="" multiple="" data-group="Дорога"  data-base="<?php echo Url::toRoute(['photobook-api/upload', 'id'=>$id, 'ref'=>$ref, 'group'=>'groupname', ]); ?>" data-url="<?php echo Url::toRoute(['photobook-api/upload', 'id'=>$id, 'ref'=>$ref, 'group'=>'Дорога', ]); ?>">
                                        </span>


                                    </form>

                                    <a href="/photobook-api/delete-group?ref=gLA9o&id=gLAAE&group=groupname"  class="btn btn-link gray btnDelete" >
                                       Удалить группу
                                    </a>
                                </div>

                                <div class="col-xs-10">
                                    <div id="files" class="files">
                                        <a class="thumb" href="#">
                                            <img src="/uploads/gLA9o/pb/gLAAE/photos/b4K91t_t.jpg" alt="">
                                        </a>
                                        <a class="thumb" href="#"><img src="/uploads/gLA9o/pb/gLAAE/photos/b6Qj6e_t.jpg" alt=""></a>
                                        <a class="thumb" href="#"><img src="/uploads/gLA9o/pb/gLAAE/photos/b6rdkm_t.jpg" alt=""></a>
                                        <a class="thumb" href="#"><img src="/uploads/gLA9o/pb/gLAAE/photos/b2wT9X_t.jpg" alt=""></a>
                                        <a class="thumb" href="#"><img src="/uploads/gLA9o/pb/gLAAE/photos/b1Abrd_t.jpg" alt=""></a>
                                        <a class="thumb" href="#"><img src="/uploads/gLA9o/pb/gLAAE/photos/b539r9_t.jpg" alt=""></a>
                                        <a class="thumb" href="#"><img src="/uploads/gLA9o/pb/gLAAE/photos/b7suwc_t.jpg" alt=""></a>
                                        <a class="thumb" href="#"><img src="/uploads/gLA9o/pb/gLAAE/photos/b5HK2Q_t.jpg" alt=""></a>
                                        <a class="thumb" href="#"><img src="/uploads/gLA9o/pb/gLAAE/photos/b7xbIW_t.jpg" alt=""></a>
                                    </div>
                                </div>
                            </div>




                            <div class="row" style="padding-top: 31px">
                                <div class="col-xs-10">
                                    <div id="progress" class="progress">
                                        <div class="progress-bar progress-bar-primary"></div>
                                    </div>
                                </div>

                                <div class="col-xs-2">
                                    <a class="btn btn-link  button-1-line btnAddGroup">
                                        <div class="button-col button-icon ">
                                            <i class="glyphicons picture"></i>
                                        </div>
                                        <div class="button-col">
                                            <?php echo Yii::t('app', 'Добавить группу'); ?>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
         */

        $output=Html::tag('div', $this->renderContent(), ['class'=>'photo-group']);

        echo $output;
    }


    private function renderContent(){


        $output=$this->renderGroupName();

        $output.='<div class="row"> <div class="col-lg-2 col-md-3">'.$this->renderReversals().$this->renderForm().$this->renderDeleteButton().'</div>';

        $output.='<div class="col-lg-10 col-md-9">'.$this->renderFiles().'</div></div>';
        $output.='<div class="row" style="padding-top: 31px"><div class="col-lg-10 col-md-9 col-sm-8">'.$this->renderProgressBar().'</div>';

        $output.='<div class="col-lg-2 col-md-3 col-sm-4">'.$this->renderAddGroupButton().'</div></div>';

        return $output;
    }

    private function renderAddGroupButton(){

        return '<a class="btn btn-link  button-1-line btnAddGroup  turn-on-editable pull-right"  data-url="'.$this->add_group_url.'"  >
                                        <div class="button-col button-icon ">
                                            <i class="glyphicons picture"></i>
                                        </div>
                                        <div class="button-col">'.Yii::t('app', 'Добавить группу').'</div>
                                    </a>';

    }

    private function renderFiles(){

        $output='';

        if(!empty($this->group_data) && !empty($this->group_data['photos'])){

            $photos=$this->group_data['photos'];

            foreach($photos as $order=>$photo_id){

                $output.=$this->renderThumb($photo_id);

            }
        }


        $output=Html::tag('div', $output, ['class'=>'files', 'id'=>'files']);
        return $output;
    }

    private function renderThumb($photo_id){


        return ThumbInGroup::widget([
            'photobook_id'=>$this->photobook_id,
            'photo_id'=>$photo_id,
            'user_id'=>$this->user_id
        ]);
    }

    private function renderProgressBar(){

        $output=Html::tag('div', '', ['class'=>'progress-bar progress-bar-primary']);

        $output=Html::tag('div', $output, ['class'=>'progress', 'id'=>'progress']);

        return $output;

    }

    private function renderDeleteButton(){
        return '<a href="'.$this->delete_template_url.'"  class="btn btn-link gray btnDelete" >'.Yii::t('app', 'Удалить группу').'</a>';
    }

    private function renderGroupName(){

        $output=Html::tag('div', $this->group_name, ['class'=>'editable', 'data-url'=>$this->change_group_name_template_url]);

        return $output;

    }

    private function renderReversals(){


        $output=Html::input('text', 'reversals', $this->reversals, ['class'=>'spinner', 'data-min'=>1, 'data-max'=>20, 'data-interval'=>1, 'data-url'=>$this->change_reversals_template_url]);

        return   Html::tag('div', '<label>Количество разворотов: </label> '.$output, ['class'=>'reversals']);

    }

    private  function renderForm(){

        $output=$this->renderUploadButton();

        $output=Html::tag('form', $output, ['action'=>$this->upload_files_url, 'enctype'=>'multipart/form-data']);

        return $output;
    }

    private function renderUploadButton(){



        return '<span class="btn btn-default button-2-line fileinput-button ladda-button" data-style="zoom-in" data-spinner-color="#993149">
                                            <!--<i class="glyphicon glyphicon-plus"></i>-->
                                            <div class="button-col button-icon ">
                                                <i class="glyphicons picture"></i>
                                            </div>
                                            <div class="button-col">'.
                                                Yii::t('app', 'Добавить<br/>фото в группу').
                                            '</div>

                                            <input type="file" class="fileupload" name="PhotobookForm[photo]" value="" multiple="" data-group="'.$this->group_name.'"  data-base="'.$this->upload_files_template_url.'" data-url="'.$this->upload_files_url.'">
                                        </span>';
    }
}
