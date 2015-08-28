<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

use yii\helpers\Url;

use frontend\widgets\UploadPhotosGroup;


use frontend\assets\UploadPhotosAsset;
use frontend\widgets\Alert;
use app\components\UserUrl;

UploadPhotosAsset::register($this);

$this->title = Yii::t('app','Загрузка фото');
$this->params['breadcrumbs'][] = $this->title;
?>




<!-- <div class="container-fluid " >
     <div class="row">
         <div class="col-xs-12" style="background-color: #ff0000;">-->

<!--

<nav id="navbar" class="navbar navbar-default" role="navigation">
    <div class="container-fluid">

        <div class="navbar-header">

            <a class="navbar-brand" ><b>ШАГ 2.</b> Загрузка фото</a>
        </div>

        <div class="navbar-form navbar-right" >

            <a class="badge">?</a>

        </div>

    </div>
</nav>-->








        <!--<ul class="breadcrumb"><li><div class="editable" data-url="<?php echo  Url::toRoute(['photobook-api/change-name', 'ref'=>$ref, 'id'=>$id, 'name'=>'newname']); ?>"><?php echo $model->name; ?></div></li>
        </ul>
-->


            <div class="well well-sm"  role="alert">

                <div class="row">
                    <div class="col-md-12">

                        <div >

                           <span class="brand-color"> <b>Выбор стиля для всех разворотов:</b><br/></span>

                            <?php foreach($styles as $style_key=>$style): ?>
                                <div class="col-md-4">
                                    <div class="style-thumb <?php if($style->id==$model->style_id) echo 'active';?> " title="<?php  echo $style->name; ?>"  data-placement="top" data-toggle="tooltip" >
                                        <a href="#" data-url="<?php echo  Url::toRoute(['photobook-api/change-style', 'photobook_id'=>$model->id, 'style_id'=>$style->id]); ?>" class="buttonChangeStyle">
                                            <?php if($style->thumb_key=='style_default' || $style->thumb_key=='default_style_thumb' || empty($style->thumb_key)): ?>
                                                <img class="style-min-thumb pull-right" src="/images/style_default.jpg"/>
                                            <?php else: ?>
                                                <img class="style-min-thumb pull-right" src="<?php echo UserUrl::styleThumb(true, $style->id).'/'.UserUrl::imageFile($style->thumb_key, UserUrl::IMAGE_THUMB) ?>"/>
                                            <?php endif; ?>
                                            <center><?php echo  $style->name; ?></center>
                                        </a>
                                        <span class="badge" > <i class="fa fa-check"></i> </span>


                                    </div>
                                    <div class="text-for-icon">
                                        <small><?php echo $style->text_for_icon; ?></small>
                                    </div>

                                </div>
                            <?php endforeach; ?>
                            <br/> <br/> <br/>
                        </div>
                    </div>
                </div>

            </div>




            <div class="row">
                <div class="col-xs-12">


                    <?php if(!empty($note_upload_page)): ?>
                    <div class="alert alert-info"  role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <?php echo $note_upload_page; ?>
                    </div>
                    <?php endif; ?>


                    <div class="groups"
                         data-ref="<?php echo $ref; ?>"
                         data-id="<?php echo $id; ?>"
                         data-rotateurl="<?php echo Url::toRoute(['photobook-api/image-rotate', 'id'=>$id, 'ref'=>$ref]); ?>"
                         data-deletephotourl="<?php echo Url::toRoute(['photobook-api/delete-photo', 'id'=>$id, 'ref'=>$ref]); ?>"
                        >



                        <?php foreach($model->photos as $group_name=>$group_data): ?>

                            <?php if(isset($group_data['type']) && $group_data['type']=='text') continue; ?>

                            <?php

                            if(!isset($group_data['reversals'])){

                                $group_data['reversals']=1;
                            }

                            echo UploadPhotosGroup::widget([

                                'group_name'=>$group_name,
                                'change_group_name_template_url' => Url::toRoute(['photobook-api/change-group-name', 'ref'=>$ref, 'id'=>$id, 'oldgroup'=>'oldgroupname', 'newgroup'=>'newgroupname']),
                                'upload_files_url'=>Url::toRoute(['photobook-api/upload', 'ref'=>$ref, 'id'=>$id, 'group'=>$group_name]),
                                'upload_files_template_url'=>Url::toRoute(['photobook-api/upload', 'ref'=>$ref, 'id'=>$id, 'group'=>'groupname']),
                                'group_data'=>$group_data,
                                'photobook_id'=>$pb_id,
                                'user_id'=>$user_id,
                                'reversals'=>$group_data['reversals'],
                                'change_reversals_template_url' => Url::toRoute(['photobook-api/change-reversals', 'ref'=>$ref, 'id'=>$id, 'reversals'=>'reversalsvalue', 'group'=>'groupname']),
                                'delete_template_url'=>Url::toRoute(['photobook-api/delete-group', 'ref'=>$ref, 'id'=>$id,  'group'=>'groupname']),
                                'add_group_url'=>Url::toRoute(['photobook-api/add-group', 'ref'=>$ref, 'id'=>$id]),

                            ]);
                            ?>


                        <?php endforeach; ?>
                    </div>


                    <div class="pull-right">

                        <a href="<?php echo Url::toRoute(['photobooks/index'])  ?>"  class="turn-on-editable btn btn-gray btnCancel"
                           data-url="<?php echo Url::toRoute(['photobook-api/layouts', 'ref'=>$ref, 'id'=>$id]); ?>" style="padding-top:15px;min-height: 55px" >

                            <span class="button-col">
                            <?php echo Yii::t('app', 'Отмена'); ?>
                            </span>
                        </a>

                        <a class="turn-on-editable btn btn-primary button-2-line"
                           data-url="<?php echo Url::toRoute(['photobook-api/edit', 'ref'=>$ref, 'id'=>$id]); ?>"  href="<?php echo Url::toRoute(['photobooks/layouts', 'ref'=>$ref, 'id'=>$id, 'reset'=>1])  ?>">

                            <span class="button-col button-icon">
                                <i class="glyphicons book_open"></i>
                            </span>
                            <span class="button-col">
                            <?php echo Yii::t('app', 'Создать<br/> фотокнигу'); ?>
                            </span>
                        </a><br/><br/><br/>
                    </div>


                </div>
            </div>


<div class="loader">

    <div class="place">
        <i class="anim glyphicons rotation_lock"></i><br/>

        <label><?php echo Yii::t('app', 'Поворачиваем...') ?></label>

    </div>
</div>



























