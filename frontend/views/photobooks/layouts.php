<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

use yii\helpers\Url;

use app\components\UserUrl;



use frontend\assets\LayoutsAsset;
LayoutsAsset::register($this);

$this->title = Yii::t('app','Выбор стиля');
$this->params['breadcrumbs'][] = $this->title;
?>


<nav id="navbar" class="navbar navbar-default" role="navigation">

    <div class="container-fluid ">
        <div class="row hidden-xs hidden-sm">
            <div class="col-lg-5  col-md-3 ">

            </div>
            <div class="col-lg-7 col-md-9">
                <p style="width: 585px; margin-right: -15px;" class="note-color pull-right">
                    <a class="badge pull-right">?</a>
                    Мультизагрузка - замена необработаных фото на обработанные.<br/>
                    <span class="brand-color">Важно!</span> Имя и размер файлов должены совподать!
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-5  col-md-3 ">
                <div class="navbar-header">
                    <a class="navbar-brand" ><b>ШАГ 3.</b> Выбор стиля</a>
                </div>
            </div>
            <div class="col-lg-7 col-md-9  header-buttons">

                <p style="width: 100%; margin-top: 5px; margin-bottom: 5px;" class="note-color pull-right hidden-lg hidden-md">
                    <a class="badge pull-right">?</a>
                    Мультизагрузка - замена необработаных фото на обработанные.<br/>
                    <span class="brand-color">Важно!</span> Имя и размер файлов должены совподать!
                </p>

                <div class="navbar-form navbar-right" >
                    <div class="btn-group">
                        <a class="btn btn-primary button-2-line btnMakePhotoZip" data-url="<?php echo Url::toRoute(['photobook-api/make-photo-zip', 'id'=>$id, 'ref'=>$ref]); ?>" >
                            <div class="button-col button-icon ">
                                <i class="glyphicons compressed"></i>
                            </div>
                            <div class="button-col">
                                <?php echo Yii::t('app', '1. Скачать ZIP-<br/>архив'); ?>
                            </div>
                        </a>
                    </div>

                    <div class="btn-group">
                        <a class="btn btn-gray button-2-line " >
                            <div class="button-col button-icon ">
                                <i class="fa fa-photo"></i>
                            </div>
                            <div class="button-col">
                                <?php echo Yii::t('app', '2. Обработать <br/>в Photoshop'); ?>
                            </div>
                            <!-- <div class="button-col button-caret">
                                 <i class="fa fa-chevron-down"></i>
                             </div>-->
                        </a>
                    </div>

                    <div class="btn-group">
                        <form action="<?php echo Url::toRoute(['photobook-api/upload-photo-for-replace', 'id'=>$id, 'ref'=>$ref]); ?>" enctype="multipart/form-data">
                        <a class="btn btn-gray button-2-line fileinput-button" >
                            <div class="button-col button-icon ">
                                <i class="glyphicons upload"></i>
                            </div>
                            <div class="button-col">
                                <?php echo Yii::t('app', '3. Загрузить фото <br/>(мультизагрузка)'); ?>
                            </div>

                            <input type="file" class="fileupload" name="PhotobookForm[photo]" value="" multiple=""  data-url="<?php echo Url::toRoute(['photobook-api/upload-photo-for-replace', 'id'=>$id, 'ref'=>$ref]); ?>">
                            <!-- <div class="button-col button-caret">
                                 <i class="fa fa-chevron-down"></i>
                             </div>-->
                        </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</nav>



<div id='replacePhotosArea' class="alert well well-sm fade in" >



    <div class="arrow" style="left: 50%;"></div>

                   <!-- <a href="#" class="btnCloseReplacePhoto pull-right" ><?php echo Yii::t('app', 'закрыть'); ?></a>-->
                    <button type="button" class="close btnCloseReplacePhoto" ><span>×</span><span class="sr-only">закрыть</span></button>


            <div class="replace-photos">

                <?php foreach($photos as $key=>$photo): ?>
                    <?php $photo_id=$photo['photo_id']; ?>
                    <?php $mtime=$photo['mtime'] ?>
                    <div data-id="<?php echo $photo_id; ?>" class="all-thumb photo_<?php echo $photo_id; ?>" >
                        <img src="<?php echo UserUrl::photobookPhotos(true, $model->id, $model->user_id).'/'.UserUrl::imageFile($photo_id, UserUrl::IMAGE_THUMB).'?v='.$mtime; ?>" data-id="<?php echo $photo_id; ?>" >
                        <a title=""  data-placement="top" data-toggle="tooltip" class="badge  tooltips <?php if(!empty($processed[$photo_id]) && $processed[$photo_id]) echo 'active'; ?> " data-original-title="Обработан" > <i class="fa fa-check"></i> </a>
                    </div>

                <?php endforeach; ?>

            </div>


</div>


<div class="row">
    <div class="col-xs-12">

        <div class="upload-errors">

        </div>
    </div>
</div>


<div class="row">
    <div class="col-xs-12">


        <div class="well well-sm"  role="alert">

                <div class="row">
                    <div class="col-md-12">
                        <label class="brand-color pull-left" style="font-size: 13px; padding-right: 15px; padding-top: 10px;">
                            <b>Выбор стиля <br/>для всех <br/>разворотов</b>
                        </label>
                        <div class="pull-left">
                            <?php foreach($styles as $style_key=>$style): ?>
                                <div class="style-thumb <?php if($style->id==$model->style_id) echo 'active';?> " title="<?php  echo $style->name; ?>"  data-placement="top" data-toggle="tooltip" >
                                    <a href="<?php echo Yii::$app->urlManager->createUrl(['photobooks/layouts', 'ref'=>$ref,  'id'=> $id, 'style_id'=>$style->id]); ?>" class="">
                                        <?php if($style->thumb_key=='style_default' || $style->thumb_key=='default_style_thumb' || empty($style->thumb_key)): ?>
                                            <img class="style-min-thumb pull-right" src="/images/style_default.jpg"/>
                                        <?php else: ?>
                                            <img class="style-min-thumb pull-right" src="<?php echo UserUrl::styleThumb(true, $style->id).'/'.UserUrl::imageFile($style->thumb_key, UserUrl::IMAGE_THUMB) ?>"/>
                                        <?php endif; ?>
                                    </a>
                                    <span class="badge" > <i class="fa fa-check"></i> </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

        </div>




        <div class="row pages-area" data-ref="<?php echo $ref; ?>" data-id="<?php echo $id; ?>">
            <?php foreach($pages as $key=>$page): ?>

                <div class="col-xs-4 col-md-3">
                    <a href="#" class="thumbnail page-<?php echo $key;?>">
                        <img width="100%" data-src="<?php echo Url::toRoute(['photobooks/page-svg-thumb', 'id'=>$id, 'ref'=>$ref]); ?>" src="<?php echo Url::toRoute(['photobooks/page-svg-thumb', 'id'=>$id, 'ref'=>$ref, 'page'=>$key]); ?>" />
                    </a>
                   <!-- <span>Разворот <?php echo ($key+1) ?></span>
                    <select class="form-control input-sm pull-right">
                        <option>Включить в книгу</option>
                        <option>На доработке</option>

                    </select>-->
                </div>
            <?php endforeach; ?>
        </div>


        <br/>  <br/>

        <div class="pull-right">

            <a href="<?php echo Url::toRoute(['photobooks/upload-photos',  'ref'=>$ref, 'id'=>$id])  ?>"  class="turn-on-editable btn btn-gray btnCancel"
               data-url="<?php echo Url::toRoute(['photobook-api/layouts', 'ref'=>$ref, 'id'=>$id]); ?>" style="padding-top:15px;min-height: 55px" >

                            <span class="button-col">
                            <?php echo Yii::t('app', 'Отмена'); ?>
                            </span>
            </a>

            <a class="turn-on-editable btn btn-primary button-2-line"
               data-url="<?php echo Url::toRoute(['photobook-api/layouts', 'ref'=>$ref, 'id'=>$id]); ?>"  href="<?php echo Url::toRoute(['photobooks/edit', 'ref'=>$ref, 'id'=>$id])  ?>">

                            <span class="button-col button-icon">
                                <i class="glyphicons book_open"></i>
                            </span>
                            <span class="button-col">
                            <?php echo Yii::t('app', 'Открыть<br/> фотокнигу'); ?>
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








