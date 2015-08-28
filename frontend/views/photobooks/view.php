<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

use yii\helpers\Url;
use app\components\UserUrl;
use common\models\PhotobookState;



use frontend\assets\PhotobookEditAsset;
PhotobookEditAsset::register($this);

$this->title = Yii::t('app',$model->name);
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
                <a class="navbar-brand" style="" ><span><?php echo $model->name; ?></span> <span class="color-gray current-state-title"><?php echo Yii::t('app', 'Обложка') ?></span></a>
            </div>

            <div class="navbar-form navbar-right" >

                <div class="btn-group">
                   <h4><?php  echo Yii::t('app', 'Цена:');?> <span id="priceDisplay"><?php echo sprintf("%.2f", $total_price); ?></span> у.е</h4>
                </div>

                <div class="btn-group">
                    <a class="btn btn-primary button-1-line btnBackCover">

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
                <a class="navbar-brand" style="" ><span class="photobook-title"><?php echo $model->name; ?></span> <span class="color-gray current-state-title"><?php echo Yii::t('app', 'Обложка') ?></span></a>

            </div>

            <div class="navbar-form navbar-right" >



                <div id="divSendToEditFromCustomer" class="btn-group" style="<?php if($photobook_state->status!=PhotobookState::STATUS_WAIT_PHOTOGRAPH_EDIT) echo 'display:none;' ?>"  >
                        <a class="btn btn-primary button-2-line "  disabled >
                            <div class="button-col button-icon ">
                                <i class="fa fa-thumbs-down"></i>
                            </div>
                            <div class="button-col">
                                <?php echo Yii::t('app', 'Отправлен <br/>на доработку'); ?>
                            </div>
                        </a>
                </div>


                <div id="divSendToPrintFromCustomer" class="btn-group" style="<?php if($photobook_state->status!=PhotobookState::STATUS_READY) echo 'display:none;' ?>"  >
                    <a class="btn btn-primary button-2-line "  disabled >
                        <div class="button-col button-icon ">
                            <i class="fa fa-thumbs-down"></i>
                        </div>
                        <div class="button-col">
                            <?php echo Yii::t('app', 'Утверждено <br/>в печать'); ?>
                        </div>
                    </a>
                </div>






                <div class="btn-group" style="<?php if($photobook_state->status!=PhotobookState::STATUS_WAIT_CUSTOMER_COMMENTS) echo 'display:none;' ?>" >
                    <a class="btn btn-primary button-2-line btnSendToEditFromCustomer"  data-url="<?php echo Url::toRoute(['photobook-api/send-to-edit-from-customer', 'key'=>$photobook_state->view_access_key]);  ?>" >
                        <div class="button-col button-icon ">
                            <i class="fa fa-thumbs-down"></i>
                        </div>
                        <div class="button-col">
                            <?php echo Yii::t('app', 'Отправить <br/>на доработку'); ?>
                        </div>
                    </a>
                </div>

                <div class="btn-group"  style="<?php if($photobook_state->status!=PhotobookState::STATUS_WAIT_CUSTOMER_COMMENTS) echo 'display:none;' ?>" >
                    <a class="btn btn-primary button-2-line btnSendToPrintFromCustomer" data-url="<?php echo Url::toRoute(['photobook-api/send-to-print-from-customer', 'key'=>$photobook_state->view_access_key]);  ?>" >
                        <div class="button-col button-icon ">
                            <i class="fa fa-thumbs-up"></i>
                        </div>
                        <div class="button-col">
                            <?php echo Yii::t('app', 'Утвердить <br/>в печать'); ?>
                        </div>
                    </a>
                </div>

            </div>

        </div>
    </nav>


    <script id="book" type="text/json">
            <?php echo json_encode($model->data); ?>
    </script>


    <script id="comments" type="text/json">
            <?php echo json_encode($photobook_state->comments); ?>
    </script>




    <div id="book-container" class="book-container" data-access="view" data-status="<?php echo $photobook_state->status; ?>" data-value=""
         data-moveurl="<?php echo Url::toRoute(['photobook-api/move-page', 'id'=>$id, 'ref'=>$ref]);  ?>"
         data-addurl="<?php echo Url::toRoute(['photobook-api/add-new-page', 'id'=>$id, 'ref'=>$ref]);  ?>"
         data-baseprice="<?php echo $base_price; ?>"
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




    <div id="newpage">
        <a href="#" class="new-page-placeholder prev" ><?php echo Yii::t('app', "Создать новую страницу"); ?></a>
        <a  href="#" class="new-page-placeholder next"><?php echo Yii::t('app', "Создать новую страницу"); ?></a>
    </div>


    <div id="tools" class="tools">
        <div class="container-fluid">



            <div class="btn-group page-control pull-right">
                <a id="bb-nav-prev" href="#" class="btn btn-tools"><i class="fa fa-angle-left"></i></a>
                <a id="bb-nav-display" href="#" class="btn btn-label">1/3</a>
                <a id="bb-nav-next" href="#" class="btn btn-tools"><i class="fa fa-angle-right "></i></a>
            </div>
        </div>
    </div>

    <div id="photos" class="photos" data-url="<?php echo Url::toRoute(['photobook-api/get-photos', 'id'=>$id, 'ref'=>$ref]); ?>" data-ref="<?php echo $ref; ?>" data-id="<?php echo $id; ?>">

        <div class="container">
            <div class="row" style="font-size: 13px; padding-top: 5px;">

                <div id="commentContainer">
                    <span id="commentTitle" style="color: #101010"></span> <span style="color: #c3c3c3">(Опишите ваши замечания к данной странице, если замечаний нет оставте пустым.)</span>
                    <textarea id="commentText" style="height: 90px;" data-index="0" data-url="<?php echo Url::toRoute(['photobook-api/save-customer-comments', 'key'=>$photobook_state->view_access_key]); ?>" class="col-lg-12 col-md-12 col-xs-12 col-sm-12"></textarea>
                </div>



            </div>
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

