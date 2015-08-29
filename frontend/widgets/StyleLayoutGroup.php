<?php

namespace frontend\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;

use yii\base\Widget;
use app\components\UserUrl;
use frontend\widgets\ThumbTemplateInGroup;



class StyleLayoutGroup extends Widget
{


    public $group_name = 'Тест1';

    public $change_group_name_template_url = '';

    public $upload_files_url='';

    public $upload_files_template_url='';

    public $group_data;

    public $style_id;

    public $change_background_color_url='';

    public $background_color='#FFFFFF';

    public $background_image='';

    public $add_group_url='';

    public $group_index=0;



    public $delete_url = '';
    public function init()
    {
        parent::init();


    }

    public function run()
    {


        /*
         *
         *  <div class="photo-group">
                    <div class="editable" data-url="<?php echo Url::toRoute(['photobook-api/change-group-name', 'ref'=>$ref, 'id'=>$id]); ?>&oldgroup=oldgroupname&newgroup=newgroupname"><?php echo $group_name; ?></div>

                     <form action="<?php echo Url::toRoute(['photobook-api/upload', 'ref'=>$ref, 'id'=>$id]); ?>&group=<?php echo $group_name; ?>">
                     <span class="btn btn-primary fileinput-button" ">
                        <i class="glyphicon glyphicon-plus"></i>
                        <span>Select files...</span>
                        <!-- The file input field used as target for the file upload widget -->
                        <input  type="file"
                                name="PotosForm[photos][]"
                                multiple data-group="<?php echo $group_name; ?>"
                                data-base="<?php echo Url::toRoute(['photobook-api/upload', 'ref'=>$ref, 'id'=>$id, 'group'=>'groupname']); ?>"
                                data-url="<?php echo Url::toRoute(['photobook-api/upload', 'ref'=>$ref, 'id'=>$id]); ?>&group=<?php echo $group_name; ?>"
                                class="fileupload" />
                    </span>
                     </form>

                    <!-- The global progress bar -->
                    <div id="progress" class="progress">
                        <div class="progress-bar progress-bar-success"></div>
                    </div>
                    <!-- The container for the uploaded files -->
                    <div id="files" class="files"></div>
                </div>
         */

        $output=Html::tag('div', $this->renderContent(), ['class'=>'photo-group photo-group-'.$this->group_index]);

        echo $output;
    }


    private function renderContent(){


        $output=$this->renderGroupName();

        $output.='<div class="row"> <div class="col-lg-2 col-md-3">'.$this->renderBackgroundColor().$this->renderForm().$this->renderDeleteButton().'</div>';
        $output.='<div class="col-lg-10 col-md-9">'.$this->renderFiles().'</div></div>';
        $output.='<div class="row" style="padding-top: 31px"><div class="col-lg-10 col-md-9 col-sm-8">'.$this->renderProgressBar().'</div>';

        //$this->renderAddGroupButton()
        $output.='<div class="col-lg-2 col-md-3 col-sm-4">'.$this->renderAddGroupButton().'</div></div>';
       /* $output.=$this->renderBackgroundColor();
        $output.=$this->renderForm();
        $output.=$this->renderProgressBar();
        $output.=$this->renderFiles();
        $output.=$this->renderDeleteButton();*/

        return $output;
    }

    private function renderFiles(){

        $output='';

        if(!empty($this->group_data) && !empty($this->group_data['template_ids'])){

            $template_ids=$this->group_data['template_ids'];

            foreach($template_ids as $ph_count=>$template_id){

                $count=intval(str_replace('ph_count_', '',$ph_count ));

                $output.=$this->renderThumb($template_id, $count);
            }
        }

        $output=Html::tag('div', $output, ['class'=>'files', 'id'=>'files']);
        return $output;
    }

    private function renderBackgroundColor(){

        $output=Html::input('text', 'background_color', $this->background_color, ['class'=>'color-picker form-control input-sm', 'style'=>'width:80%',  'data-url'=>$this->change_background_color_url]);
        return   Html::tag('div', '<label>Цвет фона: '.$output.'</label> ', ['class'=>'']);
    }

    private function renderThumb($template_id, $count=0){


        return ThumbTemplateInGroup::widget([
            'style_id'=>$this->style_id,
            'template_id'=>$template_id,
            'background_image'=>$this->background_image,
            'background_color'=>$this->background_color,
            'count'=>$count,
            'group_index'=>$this->group_index
        ]);



    }

    private function renderProgressBar(){

        $output=Html::tag('div', '', ['class'=>'progress-bar progress-bar-primary']);

        $output=Html::tag('div', $output, ['class'=>'progress', 'id'=>'progress']);

        return $output;

    }

    private function renderDeleteButton(){

        return '<a href="'.$this->delete_url.'"  class="btn btn-link gray btnDelete" >'.Yii::t('app', 'Удалить группу').'</a>';
       // return '<div class="row"><div class="col-xs-12"><a href="'.$this->delete_url.'" data-toggle="tooltip" data-placement="top" title="'.Yii::t('app', 'Удалить').'" class="btn btn-primary tooltips  btnDelete pull-right" style="padding-right: 15px;"><i class="glyphicon glyphicon-trash" style="font-size: 12px;"></i></a></div></div>';
    }

    private function renderGroupName(){

        $output=Html::tag('div', $this->group_name, ['class'=>'editable', 'data-url'=>$this->change_group_name_template_url]);
        return $output;
    }



    private  function renderForm(){

        $output=$this->renderUploadButton();
        $output=Html::tag('form', $output, ['action'=>$this->upload_files_url, 'enctype'=>'multipart/form-data']);
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


    private function renderUploadButton(){

        return '<span class="btn btn-default button-2-line fileinput-button ladda-button" data-style="zoom-in" data-spinner-color="#993149">
                                            <!--<i class="glyphicon glyphicon-plus"></i>-->
                                            <div class="button-col button-icon ">
                                                <i class="glyphicons picture"></i>
                                            </div>
                                            <div class="button-col">'.
        Yii::t('app', 'Изменить<br/>фон').
        '</div>
        <input type="file" class="fileupload" name="StyleForm[photo]" value="" multiple="" data-group="'.$this->group_name.'"  data-base="'.$this->upload_files_template_url.'" data-url="'.$this->upload_files_url.'">
                                        </span>';


    /*    $output=Html::tag('i', '', ['class'=>'glyphicon glyphicon-plus']);

        $output.=Html::input('file',
                             'StyleForm[photo]',
                             '',
                             [
                                'multiple'=>false,
                                'data-group'=>$this->group_name,
                                'data-base'=>$this->upload_files_template_url,
                                'data-url'=>$this->upload_files_url,
                                'class'=>'fileupload',

                             ]
                            );



        $output.=Html::tag('span', Yii::t('app', 'Выбрать фон...'));


        $output=Html::tag('span', $output, ['class'=>'btn btn-primary fileinput-button']);

        return $output;*/
    }
}
