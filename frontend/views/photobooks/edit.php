<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

use yii\helpers\Url;
use app\components\UserUrl;



use frontend\assets\PhotobookEditAsset;
PhotobookEditAsset::register($this);

$this->title = Yii::t('app','Редактор');
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="wrapper" class="wrapper">

    <!-- <div class="container-fluid " >
         <div class="row">
             <div class="col-xs-12" style="background-color: #ff0000;">-->



    <nav id="navbar_edit_cover" class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">


                <div class="navbar-brand" >


                    <a class="btn btn-primary" style="margin-top: -5px; " href="<?php echo Url::toRoute(['photobooks/index', 'status'=>$model->status]); ?>" ><i class="fa fa-bars" style="padding-right: 5px; "></i></a>
                    <a  ><span class="photobook-title" ><?php echo $model->name; ?></span> <span class="color-gray current-state-title"><?php echo Yii::t('app', 'Работа с обложкой') ?></span></a>
                </div>



               <!-- <a class="navbar-brand" style="" ><span><?php echo $model->name; ?></span> <span class="color-gray current-state-title"><?php echo Yii::t('app', 'Работа с обложкой') ?></span></a>-->
            </div>

            <div class="navbar-form navbar-right" >

                <div class="btn-group">
                   <h4><?php  echo Yii::t('app', 'Цена:');?> <span id="priceDisplay"><?php echo sprintf("%.2f", $total_price); ?></span> <?php echo $default_currency; ?></h4>
                </div>

                <div class="btn-group">
                    <a class="btn btn-primary button-1-line btnBackCover"  data-url="<?php echo Url::toRoute(['photobooks/send-to-print', 'id'=>$id, 'ref'=>$ref, 'back'=>'edit']);  ?>">

                        <div class="button-col">
                            <?php echo Yii::t('app', 'Далее'); ?>
                        </div>
                        <div class="button-col button-icon ">
                            <i class="fa fa-arrow-right"></i>
                        </div>

                    </a>
                </div>
            </div>
        </div>
    </nav>


    <nav id="navbar_edit_layout" class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">



                <div class="navbar-brand" >


                    <a class="btn btn-primary" style="margin-top: -5px; " href="<?php echo Url::toRoute(['photobooks/index', 'status'=>$model->status]); ?>" ><i class="fa fa-bars" style="padding-right: 5px; "></i></a>
                    <a  ><span class="photobook-title" ><?php echo $model->name; ?></span> <span class="color-gray current-state-title"><?php echo Yii::t('app', 'Работа с разворотами') ?></span></a>
                </div>

               <!-- <a class="navbar-brand" style="" ><span class="photobook-title" ><?php echo $model->name; ?></span> <span class="color-gray current-state-title"><?php echo Yii::t('app', 'Работа с разворотами') ?></span></a>-->
            </div>

            <div class="navbar-form navbar-right" >

                <div class="btn-group">
                    <a class="btn btn-primary button-1-line btnBack">
                        <div class="button-col button-icon ">
                            <i class="glyphicons ok_2"></i>
                        </div>
                        <div class="button-col">
                            <?php echo Yii::t('app', 'Готово'); ?>
                        </div>

                    </a>
                </div>
            </div>

        </div>
    </nav>

    <nav id="navbar" class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
                                 <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
              <?php

                  /*<!--  <a class="navbar-brand" href="<?php echo Url::home(); ?>"><img src="<?php echo UserUrl::logoUrl($this->params['logo_url'], UserUrl::IMAGE_SMALL,'jpg', $this->params['ref_user_id']) ?>" height="49" /></a>
                -->
              */

              ?>


                <div class="navbar-brand" >


                    <a class="btn btn-primary" style="margin-top: -5px; " href="<?php echo Url::toRoute(['photobooks/index', 'status'=>$model->status]); ?>" ><i class="fa fa-bars" style="padding-right: 5px; "></i></a>
                    <a ><span class="photobook-title" ><?php echo $model->name; ?></span> <span class="color-gray current-state-title"><?php echo Yii::t('app', 'Разворот 1') ?></span></a>
                </div>


               <!-- <a class="navbar-brand" style="" ><span class="photobook-title"><?php echo $model->name; ?></span> <span class="color-gray current-state-title"><?php echo Yii::t('app', 'Разворот 1') ?></span></a>-->

            </div>

            <div class="navbar-form navbar-right" >



                <div class="btn-group">
                    <a class="btn btn-default button-2-line btnChangeLayout">
                        <div class="button-col button-icon ">
                            <i class="glyphicons book_open"></i>
                        </div>
                        <div class="button-col">
                            <?php echo Yii::t('app', 'Изменить<br/>макет'); ?>
                        </div>

                    </a>
                </div>


                <div class="btn-group">

                    <a class="btn btn-default button-2-line btnEditPages">
                        <div class="button-col button-icon ">
                            <i class="glyphicons show_big_thumbnails"></i>
                        </div>
                        <div class="button-col">
                            <?php echo Yii::t('app', 'Редактировать<br/>развортоы'); ?>
                        </div>
                    </a>

                </div>




              <!--  <div class="btn-group">

                    <a class="btn btn-default button-2-line btnEditCover">
                        <div class="button-col button-icon ">
                            <i class="glyphicons book"></i>
                        </div>
                        <div class="button-col">
                            <?php echo Yii::t('app', 'Редактировать<br/> обложку'); ?>
                        </div>

                    </a>
                </div>-->

                <div class="btn-group">
                    <a class="btn btn-primary button-2-line btnShowGetLinkDialogLink"  >
                        <div class="button-col button-icon ">
                            <i class="fa fa-link"></i>
                        </div>
                        <div class="button-col">
                            <?php echo Yii::t('app', 'Получить ссылку для<br/> согласования'); ?>
                        </div>
                    </a>
                </div>

                <div class="btn-group">
                    <a class="btn btn-primary button-2-line " href="<?php echo Url::toRoute(['photobooks/send-to-print', 'id'=>$id, 'ref'=>$ref, 'back'=>'edit']);  ?>" >
                        <div class="button-col button-icon ">
                            <i class="fa fa-shopping-cart"></i>
                        </div>
                        <div class="button-col">
                            <?php echo Yii::t('app', 'Отправить<br/>в печать'); ?>
                        </div>
                    </a>
                </div>

            </div>

        </div>
    </nav>


    <script id="book" type="text/json">
            <?php echo json_encode($model->data); ?>
    </script>




    <div id="book-container" class="book-container"  data-access="edit" data-value=""
         data-moveurl="<?php echo Url::toRoute(['photobook-api/move-page', 'id'=>$id, 'ref'=>$ref]);  ?>"
         data-addurl="<?php echo Url::toRoute(['photobook-api/add-new-page', 'id'=>$id, 'ref'=>$ref]);  ?>"
         data-pricespread="<?php echo $price_spread; ?>"
         data-curse="<?php echo $curse; ?>"
         data-coverprice="<?php echo $cover_price; ?>"
         data-coverpricesign="<?php echo $cover_price_sign; ?>"
        >




        <div class="bb-cover-wrapper">


            <div id="bb-cover" class="bb-bookblock" style="background: none;">

            <div class="bb-item" id="sitem1">
                <div class="content" >
                    <div class="box">
                        <div id="coverFrontBackground" style="width: 100%; height: 100%; border-bottom-right-radius: 5px; border-top-right-radius: 5px; position: absolute; background-size: cover; background-image: url('<?php echo UserUrl::coverFront(true,$selected_cover->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($selected_cover->cover_front, UserUrl::IMAGE_ORIGINAL, 'png'); ?>');">

                            <div class="cover-area" style="position: absolute; width: 100%; height: 100%; ">

                                <div id="coverWindowPreview" style="position: absolute;
                                    left:<?php echo $selected_cover->window_offset_x/7.1; ?>%;
                                    top:<?php echo $selected_cover->window_offset_y/2.6; ?>%;
                                    width:<?php echo $selected_cover->window_width/7.1; ?>%;
                                    height:<?php echo $selected_cover->window_height/2.6; ?>%;
                                    background-size: contain; background-image: url('<?php echo UserUrl::photobookWindowText(true, $model->id, $model->user_id)."/".UserUrl::imageFile($model->id, UserUrl::IMAGE_ORIGINAL, 'png').'?r='.rand(0,99999999); ?>'); "></div>

                            </div>

                            <div class="cover-options" style="position: absolute; left:0%; width: 50%; height: 100%; ">

                                <h4><?php echo Yii::t('app', "Параметры обложки"); ?></h4>


                                <?php foreach($covers as $material_type=>$cover_list): ?>

                                    <h5><?php echo mb_strtoupper($material_type); ?></h5>

                                    <div class="container-fluid">
                                        <div class="row">


                                            <?php foreach($cover_list as $key=>$cover): ?>
                                                <div class="col-xs-2 col-md-2 cover-thumb ">


                                                            <a href="#" class="thumbnail cover-thumb-link <?php if( $cover->id==$selected_cover->id) echo 'active'; ?>" data-id="<?php echo $cover->id; ?>"
                                                               data-cover_front_url="<?php echo UserUrl::coverFront(true,$cover->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($cover->cover_front, UserUrl::IMAGE_ORIGINAL, 'png');  ?>"
                                                               data-cover_back_url="<?php echo UserUrl::coverBack(true,$cover->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($cover->cover_back, UserUrl::IMAGE_ORIGINAL, 'png');  ?>"
                                                               data-padded_cover_url="<?php echo UserUrl::coverPadded(true,$cover->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($cover->padded_cover, UserUrl::IMAGE_ORIGINAL, 'png');  ?>"
                                                               data-price="<?php echo $cover->price; ?>"
                                                               data-price_sign="<?php echo $cover->price_sign; ?>"
                                                               data-url="<?php echo Url::toRoute(['photobook-api/change-cover', 'id'=>$pb_id, 'cover_id'=>$cover->id]);  ?>"
                                                               style="margin-bottom: 0px; border:none; cursor: pointer;" >
                                                                <?php if($cover->thumb=='style_default' || $cover->thumb=='default_style_thumb' || empty($cover->thumb)): ?>
                                                                    <img class="style-min-thumb img-circle" src="/images/style_default.jpg"/>
                                                                <?php else: ?>
                                                                    <img class="style-min-thumb img-circle" src="<?php echo UserUrl::coverThumb(true, $cover->id).'/'.UserUrl::imageFile($cover->thumb, UserUrl::IMAGE_THUMB) ?>"/>
                                                                <?php endif; ?>

                                                            </a>

                                                </div>
                                            <?php endforeach; ?>





                                        </div>
                                    </div>

                                <?php endforeach; ?>

                                <h4><?php echo Yii::t('app', "Название книги"); ?></h4>


                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xs-6 col-sm-6">
                                            <div class="form-group">

                                                        <input id="titleLine1" data-url="<?php echo  Url::toRoute(['photobook-api/update-cover-window-image-text',  'id'=>$model->id, 'field_name'=>'title_line_1']); ?>" type="text" placeholder="Введите имя" class="inputCoverWindowText form-control  col-md-6" value="<?php echo $model->title_line_1; ?>"  maxlength="30" autocomplete="off">


                                            </div>

                                            <div class="form-group">


                                                    <input id="titleLine2" data-url="<?php echo  Url::toRoute(['photobook-api/update-cover-window-image-text',  'id'=>$model->id, 'field_name'=>'title_line_2']); ?>" type="text" placeholder="Введите '&'" class="inputCoverWindowText form-control  col-md-6" value="<?php echo $model->title_line_2; ?>"  maxlength="30" autocomplete="off">


                                            </div>

                                            <div class="form-group">


                                                    <input id="titleLine3" data-url="<?php echo  Url::toRoute(['photobook-api/update-cover-window-image-text',  'id'=>$model->id, 'field_name'=>'title_line_3']); ?>" type="text" placeholder="Введите имя 2" class="inputCoverWindowText form-control  col-md-6" value="<?php echo $model->title_line_3; ?>"  maxlength="30" autocomplete="off">


                                            </div>

                                            <div class="form-group">


                                                    <input id="titleLine4" data-url="<?php echo  Url::toRoute(['photobook-api/update-cover-window-image-text',  'id'=>$model->id, 'field_name'=>'title_line_4']); ?>" type="text" placeholder="Введите Ваш копирайт" class="inputCoverWindowText form-control  col-md-6" value="<?php echo $model->title_line_4; ?>"  maxlength="30" autocomplete="off">


                                            </div>
                                        </div>
                                     </div>


                            </div>
                            <div class="cover" style="position: absolute; left:50%; width: 50%; height: 100%; "></div>



                        </div>
                    </div>
                </div>
            </div>




            <div class="bb-item" id="sitem2">
                <div class="content" >
                    <div class="box " style=" ">

                        <div id="coverPaddedBackground" style="width: 100%; height: 100%; position: absolute; border-radius: 3px; background-size: cover; background-image: url('<?php echo UserUrl::coverPadded(true,$selected_cover->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($selected_cover->padded_cover, UserUrl::IMAGE_ORIGINAL, 'png'); ?>');">

                            <div class="bb-custom-wrapper">

                                <div id="bb-bookblock" class="bb-bookblock">





                                    <?php foreach($pages as $key=>$page): ?>
                                        <div class="bb-item bb-pitem" id="item<?php echo $key; ?>">
                                            <div class="content" >
                                                <div class="box">

                                                    <?php if($key==1): ?>
                                                    <div class="bb-vellum-wrapper">

                                                        <div id="bb-vellum" class="bb-bookblock" >
                                                            <div class="bb-item " id="vitem1"  >
                                                                <div class="content"  >
                                                                    <div class="box" >
                                                                        <div style="width: 100%; height: 100%; position: absolute;   ">
                                                                            <div style="width: 100%; height: 100%; position: absolute;   background-size: cover; background-image: url('<?php echo UserUrl::stylePaddedPassepartout(true,$style->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($style->padded_passepartout_key, UserUrl::IMAGE_ORIGINAL); ?>');">

                                                                                <div class="svg svg-<?php echo $key; ?>"  data-index="<?php echo $key; ?>" style=" height: 100%;">
                                                                                    <svg  height="100%" viewBox="0 0 700 250">
                                                                                        <?php if(!empty($page['svg'])) echo $page['svg']; ?>
                                                                                    </svg>

                                                                                </div>
                                                                            </div>
                                                                            <div class="" style="position: absolute; left:50%; width: 50%; height: 100%; background-color: #ffffff; opacity: 0.9;  filter: alpha(Opacity=70); "></div>
                                                                            <div class="tracing-text-background" style="position: absolute; left:50%; width: 50%; height: 100%; background-size: contain; background-image: url('<?php echo UserUrl::photobookTracingText(true,$model->id, $model->user_id )."/". UserUrl::imageFile($model->id, UserUrl::IMAGE_ORIGINAL, 'png'); ?>');    "></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="bb-item" id="vitem2" >
                                                                <div class="content" >
                                                                    <div class="box" >
                                                                        <div style="width: 100%; height: 100%; position: absolute;   ">
                                                                            <div style="width: 100%; height: 100%; position: absolute;   background-size: cover; background-image: url('<?php echo UserUrl::stylePaddedPassepartout(true,$style->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($style->padded_passepartout_key, UserUrl::IMAGE_ORIGINAL); ?>');">

                                                                                <div class="svg svg-<?php echo $key; ?>"  data-index="<?php echo $key; ?>" style=" height: 100%;">
                                                                                    <svg  height="100%" viewBox="0 0 700 250">
                                                                                        <?php if(!empty($page['svg'])) echo $page['svg']; ?>
                                                                                    </svg>

                                                                                </div>
                                                                            </div>
                                                                            <div class="" style="position: absolute; left:0%; width: 50%; height: 100%; background-color: #ffffff; opacity: 0.9;  filter: alpha(Opacity=70); "></div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>



                                                    </div>

                                                    <?php else: ?>

                                                        <div style="width: 100%; height: 100%; position: absolute;   background-size: cover; background-image: url('<?php echo UserUrl::stylePaddedPassepartout(true,$style->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($style->padded_passepartout_key, UserUrl::IMAGE_ORIGINAL); ?>');">

                                                            <div class="svg svg-<?php echo $key; ?>"  data-index="<?php echo $key; ?>" style=" height: 100%;">
                                                                <svg  height="100%" viewBox="0 0 700 250">
                                                                    <?php if(!empty($page['svg'])) echo $page['svg']; ?>
                                                                </svg>

                                                            </div>
                                                        </div>
                                                    <?php endif; ?>

                                                       <!-- <div style="width: 100%; height: 100%; position: absolute;   ">
                                                            <div class="" style="position: absolute; left:50%; width: 50%; height: 100%; background-color: #ffffff; opacity: 0.7;  filter: alpha(Opacity=70); "></div>
                                                        </div>-->

                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>


                                </div>

                                <div data-changelayouturl="<?php echo Url::toRoute(['photobook-api/change-layout', 'id'=>$id, 'ref'=>$ref]);  ?>"
                                     data-actionurl="<?php echo Url::toRoute(['photobook-api/change-action', 'id'=>$id, 'ref'=>$ref]);  ?>"
                                     data-swapurl="<?php echo Url::toRoute(['photobook-api/swap-photo', 'id'=>$id, 'ref'=>$ref]);  ?>"
                                     data-replaceurl="<?php echo Url::toRoute(['photobook-api/replace-photo', 'id'=>$id, 'ref'=>$ref]);  ?>"
                                     data-addphotourl="<?php echo Url::toRoute(['photobook-api/add-photo', 'id'=>$id, 'ref'=>$ref]); ?>"
                                     data-addtexturl="<?php echo Url::toRoute(['photobook-api/add-text', 'id'=>$id, 'ref'=>$ref]); ?>"
                                     data-changetexturl="<?php echo Url::toRoute(['photobook-api/change-text', 'id'=>$id, 'ref'=>$ref]); ?>"
                                     data-deletepageurl="<?php echo Url::toRoute(['photobook-api/delete-page', 'id'=>$id, 'ref'=>$ref]); ?>"
                                     data-deleteurl="<?php echo Url::toRoute(['photobook-api/delete-placeholder', 'id'=>$id, 'ref'=>$ref]); ?>"
                                     data-url="<?php echo Url::toRoute(['photobook-api/set-image-pos-and-scale', 'id'=>$id, 'ref'=>$ref]); ?>" data-rotateurl="<?php echo Url::toRoute(['photobook-api/image-rotate', 'id'=>$id, 'ref'=>$ref]); ?>" class="page-handlers page-handlers-0" data-index="0">&nbsp;</div>




                                <div class="divider"></div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>



             <div class="bb-item" id="sitem3">
                 <div class="content" >
                     <div class="box">
                         <div  id="coverBackBackground" style="width: 100%; height: 100%; position: absolute;  border-bottom-left-radius: 5px; border-top-left-radius: 5px; background-size: contain; background-image: url('<?php echo UserUrl::coverBack(true,$selected_cover->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($selected_cover->cover_back, UserUrl::IMAGE_ORIGINAL, 'png'); ?>');">
                             <div class="bb-custom-wrapper"></div>
                         </div>
                     </div>
                 </div>
             </div>
        </div>

        </div>

    </div>

    <div id="edit_layout_area_2" style="position:absolute; left:-40000px;">

        <?php foreach($pages as $key=>$page): ?>

            <?php if($photobook_thumb_as_object): ?>
                <object style=" "
                        type="image/svg+xml"
                        width="100%"
                        data-src="<?php echo Url::toRoute(['photobooks/page-svg-thumb', 'id'=>$id, 'ref'=>$ref]); ?>"
                        data="<?php echo Url::toRoute(['photobooks/page-svg-thumb', 'id'=>$id, 'ref'=>$ref, 'page'=>$key, 'v'=>rand(0,999999999)]); ?>">
                </object>

            <?php else: ?>

                <img src="<?php echo Url::toRoute(['photobooks/page-svg-thumb', 'id'=>$id, 'ref'=>$ref, 'page'=>$key, 'v'=>rand(0,999999999)]); ?>" />
            <?php endif; ?>



        <?php endforeach; ?>

    </div>

    <div id="edit_layout_area" class="">

        <div class="container-fluid">
            <div class="row pages-area">
                <?php foreach($pages as $key=>$page): ?>


                    <div data-index="<?php echo $key; ?>" data-after="<?php  echo $key-1; ?>" class="col-xs-1 col-md-1 div-add-page-placeholder <?php   echo ' div-add-page-placeholder-'.$key;  if($key<=1 ) echo ' disabled' ?>" >
                        <?php if($key>1 ): ?>
                            <i class="glyphicons plus"></i>
                        <?php endif; ?>
                    </div>
                    <div class="col-xs-3 col-md-3 div-add-photo-placeholder div-add-photo-placeholder-<?php echo $key; ?> <?php if($key<=1 || $key>=count($pages)-1) echo 'disabled'; ?>"  data-index="<?php echo $key; ?>" >
                        <div data-index="<?php echo $key; ?>" class="subdiv <?php if($key<=1 || $key>=count($pages)-1) echo 'disabled'; ?>" style="position: relative; width: 100%; right: auto; height: 100%; bottom: auto; left: 0px; top: 0px;" >
                            <a href="#" class="thumbnail svg-thumb svg-thumb-<?php echo $key; ?> " data-index="<?php echo $key; ?>">




                                <img alt="" <?php if($key<=1 || $key>=count($pages)-1) echo 'draggable="false" style="pointer-events: none; cursor:default;"'; ?>"  width="100%" data-src="<?php echo Url::toRoute(['photobooks/page-svg-thumb', 'id'=>$id, 'ref'=>$ref]); ?>" src="<?php echo Url::toRoute(['photobooks/page-svg-thumb', 'id'=>$id, 'ref'=>$ref, 'page'=>$key, 'v'=>rand(0,999999999)]); ?>" />
                            </a>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?php if(isset( $page['flyleaf']) && $page['flyleaf']): ?>

                                    <span class="label"><?php echo Yii::t('app', 'Форзац') ?></span>
                                <?php else: ?>

                                    <span class="label"><?php echo Yii::t('app', 'Разворот ') ?><?php echo $key; ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <?php if(!isset($page['flyleaf']) || (isset($page['flyleaf']) && !$page['flyleaf'])): ?>
                                <select class="form-control action-select-<?php echo $key; ?>" data-index="<?php echo $key; ?>">
                                    <option value="print" style="color: #00ee00;" <?php  if(!empty($page['action']) && $page['action']=='print') echo 'selected'; ?>>Печатать</option>
                                    <option value="processing" style="color: #999;" <?php  if(!empty($page['action']) && $page['action']=='processing') echo 'selected'; ?>>На доработке</option>
                                    <option value="delete" style="color: #ff0000;">Удалить</option>
                                </select>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>

                <?php endforeach; ?>
                <div  data-index="<?php echo count($pages); ?>" data-after="<?php  echo  count($pages)-1; ?>"  class="col-xs-1 col-md-1 div-add-page-placeholder disabled"  >
                 <!--   <i class="glyphicons plus"></i>-->
                </div>

            </div>
        </div>
        <br/>  <br/>
    </div>



    <div id="newpage">
        <a href="#" class="new-page-placeholder prev" ><?php echo Yii::t('app', "Создать новую страницу"); ?></a>
        <a  href="#" class="new-page-placeholder next"><?php echo Yii::t('app', "Создать новую страницу"); ?></a>
    </div>


    <div id="tools" class="tools">
        <div class="container-fluid">
            <div class="btn-group">
                <div class="groups">
                    <a href="#" class="btn btn-tools active all btnSelectGroup" data-value="0"><?php echo Yii::t('app', "все фотографии"); ?></a>

                    <?php foreach($model->photos as $group_name=>$photo): ?>
                        <a href="#" class="btn btn-tools btnSelectGroup" data-value="<?php if(isset($photo['type'])) echo $photo['type']; else echo $group_name; ?>"><?php echo $group_name; ?></a>

                    <?php endforeach; ?>
                </div>
            </div>

            <div class="btn-group">
                <a href="#" class="btn btn-tools  btnAddGroup" data-url="<?php echo Url::toRoute(['photobook-api/add-group', 'id'=>$id, 'ref'=>$ref]); ?>"><?php echo Yii::t('app', "+ Добавить группу"); ?></a>
            </div>
            <div class="btn-group page-control pull-right">
                <a id="bb-nav-prev" href="#" class="btn btn-tools"><i class="fa fa-angle-left"></i></a>
                <a id="bb-nav-display" href="#" class="btn btn-label">1/3</a>
                <a id="bb-nav-next" href="#" class="btn btn-tools"><i class="fa fa-angle-right "></i></a>
            </div>
        </div>
    </div>

    <div id="photos" class="photos" data-url="<?php echo Url::toRoute(['photobook-api/get-photos', 'id'=>$id, 'ref'=>$ref]); ?>" data-ref="<?php echo $ref; ?>" data-id="<?php echo $id; ?>">
        <div class="photos-left">
            <div class="photos-cont">

            </div>
        </div>

        <div class="photos-right groups-buttons">
            <span class="fileuplod-cont" style="display: none;">
                <form action="<?php echo Url::toRoute(['photobook-api/upload', 'id'=>$id, 'ref'=>$ref]); ?>" enctype="multipart/form-data">
                    <span class="btn btn-default button-2-line btnUploadPhotos fileinput-button ladda-button" data-style="zoom-in" data-spinner-color="#993149">
                        <!--<i class="glyphicon glyphicon-plus"></i>-->
                        <div class="button-col button-icon ">
                             <i class="fa fa-archive"></i>
                        </div>
                        <div class="button-col">
                            <?php echo Yii::t('app', 'Добавить<br/>фото в группу'); ?>
                        </div>

                        <input type="file" class="fileupload" name="PhotobookForm[photo]" value="" multiple=""  data-base="<?php echo Url::toRoute(['photobook-api/upload', 'id'=>$id, 'ref'=>$ref]); ?>" >
                    </span>
                </form>
            </span>


            <span class="btn btn-default button-2-line btnAddNewText " >
                        <!--<i class="glyphicon glyphicon-plus"></i>-->
                        <div class="button-col button-icon ">
                            <i class="glyphicons text_bigger"></i>
                        </div>
                        <div class="button-col">
                            <?php echo Yii::t('app', 'Добавить<br/>текст'); ?>
                        </div>


            </span>

            <a class="btn btn-default button-2-line btnDeleteGroup "  data-url="<?php echo Url::toRoute(['photobook-api/delete-group', 'id'=>$id, 'ref'=>$ref]); ?>" style="width: 173px;  height: 50px;">
                <div class="button-col button-icon ">
                    <i class="fa fa-trash"></i>
                </div>
                <div class="button-col">
                    <?php echo Yii::t('app', 'Удалить<br/>группу'); ?>
                </div>
            </a>
        </div>

    </div>




    <div class="loader">

        <div class="place">
            <i class="anim glyphicons rotation_lock"></i><br/>
            <label><?php echo Yii::t('app', 'Поворачиваем...') ?></label>
        </div>
    </div>

    <div class="start-loader">
        <div class="place">
            <i class="anim glyphicons repeat"></i><br/>

            <label><?php echo Yii::t('app', 'Открываем книгу...') ?></label>

        </div>
    </div>


</div>



<!-- Modal -->
<div class="modal fade" id="dialogAddText" tabindex="-1" role="dialog" aria-labelledby="dialogAddTextLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="dialogAddTextLabel">Добавить текст</h4>
            </div>
            <div class="modal-body">
                <textarea id="textEdit" style="width: 100%; height: 75px;" placeholder="Введите текст" maxlength="120"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btnCancelChangeText pull-left" data-dismiss="modal" >Отменить</button>
                <button type="button" class="btn btn-default btnDeleteText pull-left"  >Удалить</button>
                <button type="button" class="btn btn-primary btnAddText pull-right"  data-url="<?php echo Url::toRoute(['photobook-api/add-text', 'id'=>$id, 'ref'=>$ref]); ?>">Сохранить</button>
                <button type="button" class="btn btn-primary btnChangeText pull-right">Сохранить</button>
            </div>
        </div>
    </div>
</div>




<!-- Modal -->
<div class="modal fade" id="dialogGetLink" tabindex="-1" role="dialog" aria-labelledby="dialogGetLinkLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="dialogGetLinkLabel">Сссылка на согласование</h4>
            </div>
            <div class="modal-body">





                <div id="viewLinkNotExistsForm" <?php if(!empty($model->view_access_key)) echo 'style="display:none;"' ?> >
                    <p>Сформировать ссылку для согласования книги и изменить статус проекта на "Отправлен на согласование"</p>
                    <button type="button" class="btn btn-primary btnGetLinkForCustomer" data-loading-text="<?php echo Yii::t('app', 'Создание ссылки...'); ?>" data-url="<?php echo Url::toRoute(['photobook-api/recieve-link-for-customer', 'id'=>$id, 'ref'=>$ref]); ?>" >Сформировать ссылку</button>
                </div>


                <div id="viewLinkExistsForm"  <?php if(empty($model->view_access_key)) echo 'style="display:none;"' ?> >

                    <p>Ссылка уже сформирована, вы можите ее отключить нажав по кнопки ниже.</p>
                    <button type="button" data-loading-text="<?php echo Yii::t('app', 'Удаление ссылки...'); ?>" class="btn btn-primary btnDeleteLinkForCustomer" data-url="<?php echo Url::toRoute(['photobook-api/delete-link-for-customer', 'id'=>$id, 'ref'=>$ref]); ?>" >Деактивировать ссылку</button>
                </div>


                <br/> <br/>

                <div id="linkFormContent" <?php if(empty($model->view_access_key)) echo 'style="display:none;"' ?> >


                        <div style="padding: 5px; border: solid 1px #cccccc;">
                            <?php echo Yii::t('app', 'Добрый день!') ?><br/>
                            <?php echo Yii::t('app', 'По этой ссылке вы можете просмотреть макет Вашей будущей книги.') ?><br/>
                            <div id="viewLinkBox">
                            <?php if(!empty($model->view_access_key)): ?>
                                <?php echo Url::toRoute(['photobooks/view', 'key'=> $model->view_access_key], true); ?>
                            <?php endif; ?>
                            </div>
                        </div>
                        <br/>

                        <div class="container-fluid">
                            <div class="row">
                                <input id="inputCustomerEmail" class="col-lg-6 text" placeholder="<?php echo Yii::t('app', 'Email клиента'); ?>" style=" padding-top: 6px; padding-bottom: 6px;" />
                                <button type="button" class="col-lg-6 btn btn-primary btnSendEmailWithToCustomer" data-url="<?php echo Url::toRoute(['photobook-api/send-email-with-link-to-customer', 'id'=>$id, 'ref'=>$ref]); ?>" >Отправить на утверждение</button>
                            </div>
                        </div>


                </div>


            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary btnDialogShowLinkClose pull-right">Закрыть</button>
            </div>
        </div>
    </div>
</div>

