<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use app\components\UserUrl;
use app\components\AlphaId;
use yii\widgets\LinkPager;
use common\models\Photobook;
use yii\widgets\DetailView;
use common\components\Utils;

$this->title = 'Фотокнига';

$this->title = Yii::t('app', 'Заказы пользователей');
$this->params['breadcrumbs'][] = $this->title;

/*echo $work_orders[0]->name;
print_r($work_orders);*/


use frontend\assets\SuperAdminUsersAsset;
SuperAdminUsersAsset::register($this);


?>

<div class="photobook-index">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">

                <div class="row">
                    <div class="col-xs-12">
                        <h2 ><?= Html::encode($this->title); ?></h2>
                    </div>
                </div>
                <hr />
            </div>
        </div>

        <div class="row" style="padding-bottom: 5px;">
            <div class="col-xs-12">

                <div class="row">
                    <div class="col-xs-12">
                        <!--<button  class="btn btn-primary pull-right btnAddPhotobook"><?php echo Yii::t('app', 'Добавить заказ'); ?></button>-->
                    </div>
                </div>

            </div>
        </div>

        <div  class="row">
            <div class="col-xs-3">


                <div class="list-group">
                <?php foreach($sidemenus as $key=>$menuitem): ?>

                    <a href="<?php echo Yii::$app->urlManager->createUrl(['super-admin/photobooks', 'status'=>$menuitem['status']]); ?>" class="list-group-item <?php if($status==$menuitem['status']) echo 'active'; ?>">
                        <?php if($menuitem['count']>0): ?><span class="badge"><?php echo  $menuitem['count'] ; ?></span><?php endif; ?>
                        <?php echo $menuitem['title'] ?>
                    </a>
                <?php endforeach; ?>
               </div>
            </div>
            <div class="col-xs-9">



                <!-- Tab panes -->


                        <?php if(count($orders)>0): ?>
                        <?php foreach($orders as $key=>$order): ?>

                                <div class="photobook-item">
                                <div class="photobook-order">


                                        <div class="row">

                                            <div class="col-xs-6">
                                                <span class="project-title"><?php echo trim($order->name); ?></span>
                                            </div>
                                            <div class="col-xs-6">
                                                <!--<div class="photobook-index-date-time pull-right">
                                                    <?php echo Yii::t('app', 'Создана: {created}', ['created'=>date("d-m-Y h:i:s",  $order->created_at)]) ?>
                                                    <?php echo Yii::t('app', 'Изменена: {created}', ['created'=>date("d-m-Y h:i:s",  $order->updated_at)]) ?>
                                                </div>-->
                                            </div>
                                        </div>



                                        <div class="row">
                                            <div class="col-xs-4">
                                                <a href="#" class="thumbnail" style="width: 100%;">

                                                    <?php  if(!$photobook_thumb_as_object): ?>
                                                    <img src="<?php echo Url::toRoute(['photobooks/page-svg-thumb', 'id'=>AlphaId::id($order->id), 'ref'=>AlphaId::id($order->user_id), 'page'=>1]); ?>">

                                                    <?php else: ?>

                                                    <object style=" "
                                                            type="image/svg+xml"
                                                            width="100%"
                                                            data-src="<?php echo Url::toRoute(['photobooks/page-svg-thumb', 'id'=>AlphaId::id($order->id), 'ref'=>AlphaId::id($order->user_id)]); ?>"
                                                            data="<?php echo Url::toRoute(['photobooks/page-svg-thumb', 'id'=>AlphaId::id($order->id), 'ref'=>AlphaId::id($order->user_id), 'page'=>1, 'v'=>rand(0,999999999)]); ?>">
                                                    </object>

                                                    <?php endif; ?>

                                                </a>
                                            </div>
                                            <div class="col-xs-4">

                                                <!--
                                                  const  STATUS_NEW = 1;
    const  STATUS_SENT_TO_CUSTOMER=2;
    const  STATUS_WAIT_EDIT_FROM_CUSTOMER=3;
    const  STATUS_SENT_TO_PRINT=4;
    const  STATUS_READY_FOR_PRINT_WAIT_PAYMENT=5;
    const  STATUS_READY_SENT_TO_PRINT=6;
    const  STATUS_READY=7;
    const  STATUS_SENT_TO_CLIENT=8;
    const  STATUS_RECEIVED_FEEDBACK=9;
    const  STATUS_ARCHIVE = 10;
                                                -->

                                                <span class="project-status-title"><?php echo Yii::t('app', 'Статус проекта:') ?></span><br/>
                                                <span class="project-status">
                                                    <?php if($order->status==Photobook::STATUS_NEW): ?>
                                                        <?php echo Yii::t('app', 'Новый проект'); ?>
                                                    <?php elseif($order->status==Photobook::STATUS_SENT_TO_CUSTOMER): ?>

                                                        <?php echo Yii::t('app', 'Отправлен на согласование'); ?>

                                                        <?php echo Utils::timeAgo($order->change_status_at); ?>
                                                    <?php elseif($order->status==Photobook::STATUS_WAIT_EDIT_FROM_CUSTOMER): ?>

                                                        <?php echo Yii::t('app', 'Клиент оставил свои комментарии'); ?>

                                                        <?php echo Utils::timeAgo($order->change_status_at); ?>

                                                    <?php elseif($order->status==Photobook::STATUS_SENT_TO_PRINT) : ?>

                                                        <?php echo Yii::t('app', 'Клиент утвердил в печать'); ?>

                                                        <?php echo Utils::timeAgo($order->change_status_at); ?>

                                                    <?php elseif($order->status==Photobook::STATUS_READY_FOR_PRINT_WAIT_PAYMENT): ?>

                                                        <?php echo Yii::t('app', 'Готово к печати, ожидает оплаты'); ?>

                                                        <?php echo Utils::timeAgo($order->change_status_at); ?>


                                                    <?php elseif($order->status==Photobook::STATUS_READY_FOR_PRINT_PAID): ?>

                                                        <?php echo Yii::t('app', 'Готово к печати, оплачено'); ?>

                                                        <?php echo Utils::timeAgo($order->change_status_at); ?>

                                                    <?php elseif($order->status==Photobook::STATUS_READY_SENT_TO_PRINT): ?>

                                                        <?php echo Yii::t('app', 'Отправлен в производство') ?>

                                                        <?php echo Utils::timeAgo($order->change_status_at); ?>

                                                    <?php elseif($order->status==Photobook::STATUS_READY): ?>

                                                        <?php echo Yii::t('app', 'Готово') ?>

                                                        <?php echo Utils::timeAgo($order->change_status_at); ?>

                                                    <?php elseif($order->status==Photobook::STATUS_READY_SENT_TO_CLIENT): ?>

                                                        <?php echo Yii::t('app', 'Отправлен') ?>

                                                        <?php echo Utils::timeAgo($order->change_status_at); ?>

                                                    <?php elseif($order->status==Photobook::STATUS_RECEIVED_FEEDBACK): ?>

                                                        <?php echo Yii::t('app', 'Получен') ?>

                                                        <?php echo Utils::timeAgo($order->change_status_at); ?>

                                                    <?php elseif($order->status==Photobook::STATUS_ARCHIVE): ?>


                                                        <?php echo Yii::t('app', 'Архив') ?>

                                                    <?php endif; ?>

                                                </span>

                                            </div>

                                            <div class="col-xs-4">

                                                <div class="photobook-index-tools">






                                                    <a class="pull-right tooltips"  href="<?php echo Url::toRoute(['photobooks/view-book', 'ref'=>AlphaId::id($order->user_id), 'id'=>AlphaId::id($order->id), 'back_url'=>Yii::$app->request->url]); ?>" >
                                                        <?php echo Yii::t('app', 'Посмотреть'); ?>
                                                    </a><br/>

                                                    <!--
                                                    <a class="pull-right tooltips"  href="<?php echo Url::toRoute(['photobooks/edit', 'ref'=>AlphaId::id($order->user_id), 'id'=>AlphaId::id($order->id), 'back_url'=>Yii::$app->request->url]); ?>" >
                                                        <?php echo Yii::t('app', 'Редактировать'); ?>
                                                    </a><br/>
                                                    -->





                                                    <?php if($order->status==Photobook::STATUS_READY_FOR_PRINT_PAID ):
                                                    ?>
                                                    <a class="pull-right tooltips"  href="<?php echo Url::toRoute(['photobooks/send-to-print', 'ref'=>AlphaId::id($order->user_id), 'id'=>AlphaId::id($order->id)]); ?>"   >
                                                        <?php echo Yii::t('app', 'Отправить в производство'); ?>
                                                    </a><br/>

                                                    <?php endif; ?>


                                                    <?php if($order->status==Photobook::STATUS_READY_SENT_TO_CLIENT ||
                                                    $order->status==Photobook::STATUS_RECEIVED_FEEDBACK ):
                                                    ?>
                                                    <a class="pull-right tooltips"  href="#"  data-ref="<?php echo AlphaId::id($order->user_id); ?>" data-id="<?php echo AlphaId::id($order->id); ?>" >
                                                        <?php echo Yii::t('app', 'Посмотреть отзыв'); ?>
                                                    </a><br/>

                                                    <?php endif; ?>


                                                    <?php if($order->status==Photobook::STATUS_READY_FOR_PRINT_WAIT_PAYMENT ||
                                                             $order->status==Photobook::STATUS_READY_FOR_PRINT_PAID):
                                                    ?>
                                                        <a class="pull-right tooltips"  href="<?php echo Url::toRoute(['photobooks/get-invoice',  'pb_id'=>$order->id, 'back_url'=>Yii::$app->request->url]); ?>" >
                                                            <?php echo Yii::t('app', 'Посмотреть счет'); ?>

                                                        </a><br/>

                                                    <?php endif; ?>




                                                        <a href="<?php echo Url::toRoute(['photobook-api/delete', 'id'=>$order->id]); ?>" class="pull-right btnDelete" data-bootbox-confirm="<?php echo Yii::t('app', 'Вы уверены, что хотите удалить эту фотокнигу?') ?>"   >
                                                            <?php echo Yii::t('app', 'Удалить'); ?>
                                                        </a><br/>






                                                    <!--

                                                    <a class="pull-right tooltips btnPhotobookCopy"  href="#" data-url="<?php echo Url::toRoute(['photobook-api/copy-to-user', 'id'=>AlphaId::id($order->id), 'ref'=>AlphaId::id($order->user_id), 'user_id'=>$order->user_id]); ?>"   >
                                                        <?php echo Yii::t('app', 'Копировать'); ?>
                                                    </a><br/>
                                                    -->





                                                    <!--

                                                    <a href="<?php echo Url::toRoute(['photobook-api/delete', 'id'=>$order->id]); ?>" class="pull-right btnDelete" data-bootbox-confirm="<?php echo Yii::t('app', 'Вы уверены, что хотите удалить эту фотокнигу?') ?>"   >
                                                        <?php echo Yii::t('app', 'Удалить'); ?>
                                                    </a><br/>

                                                    -->





                                                </div>

                                            </div>
                                        </div>


                                </div>
                                <div class="highlight">
                                    <!--<div class="photobook-order-footer"><?php echo Yii::t('app', 'Статус соглосования'); ?></div>-->
                                </div>
                                </div>



                        <?php endforeach; ?>
                        <?php else: ?>

                        <?php echo Yii::t('app', 'Заказов не найдено'); ?>

                        <?php endif; ?>



                <?php echo LinkPager::widget([
                    'pagination' => $pages,
                ]); ?>





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



<!-- Modal -->
<div class="modal fade" id="dialogGetLink" tabindex="-1" role="dialog" aria-labelledby="dialogGetLinkLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="dialogGetLinkLabel">Сссылка на согласование</h4>
            </div>
            <div class="modal-body">





                <div id="viewLinkNotExistsForm" <?php  echo 'style="display:none;"' ?> >
                    <p>Сформировать ссылку для согласования книги и изменить статус проекта на "Отправлен на согласование"</p>
                    <button type="button" class="btn btn-primary btnGetLinkForCustomer" data-loading-text="<?php echo Yii::t('app', 'Создание ссылки...'); ?>" data-url="" >Сформировать ссылку</button>
                </div>


                <div id="viewLinkExistsForm"  <?php  echo 'style="display:none;"' ?> >

                    <p>Ссылка уже сформирована, вы можите ее отключить нажав по кнопки ниже.</p>
                    <button type="button" data-loading-text="<?php echo Yii::t('app', 'Удаление ссылки...'); ?>" class="btn btn-primary btnDeleteLinkForCustomer" data-url="" >Деактивировать ссылку</button>
                </div>


                <br/> <br/>

                <div id="linkFormContent" <?php  echo 'style="display:none;"' ?> >


                    <div style="padding: 5px; border: solid 1px #cccccc;">
                        <?php echo Yii::t('app', 'Добрый день!') ?><br/>
                        <?php echo Yii::t('app', 'По этой ссылке вы можете просмотреть макет Вашей будущей книги.') ?><br/>
                        <div id="viewLinkBox">

                        </div>
                    </div>
                    <br/>

                    <div class="container-fluid">
                        <div class="row">
                            <input id="inputCustomerEmail" class="col-lg-6 text" placeholder="<?php echo Yii::t('app', 'Email клиента'); ?>" style=" padding-top: 6px; padding-bottom: 6px;" />
                            <button type="button" class="col-lg-6 btn btn-primary btnSendEmailWithToCustomer" data-url="" >Отправить на утверждение</button>
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





<!-- Modal -->
<div class="modal fade" id="dialogAddPhotobook" tabindex="-1" role="dialog" aria-labelledby="dialogAddPhotobook" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="dialogAddPhotobook">Создание новой фотокниги</h4>
            </div>
            <div class="modal-body">



                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-12">

                            <label><?php echo Yii::t('app', 'Имя 1'); ?></label>
                            <input id="inputName1" class="form-control text" placeholder="<?php echo Yii::t('app', 'Введите имя'); ?>" style=" padding-top: 6px; padding-bottom: 6px;" />

                            <label><?php echo Yii::t('app', 'Символ &'); ?></label>
                            <input id="inputAnd" class="form-control text" placeholder="<?php echo Yii::t('app', "Введите '&'"); ?>" style=" padding-top: 6px; padding-bottom: 6px;" />

                            <label><?php echo Yii::t('app', 'Имя 2'); ?></label>
                            <input id="inputName2" class="form-control text" placeholder="<?php echo Yii::t('app', "Введите имя 2"); ?>" style=" padding-top: 6px; padding-bottom: 6px;" />

                            <label><?php echo Yii::t('app', 'Копирайт'); ?></label>
                            <input id="inputCopyright" class="form-control text" placeholder="<?php echo Yii::t('app', "Введите Ваш копирайт"); ?>" style=" padding-top: 6px; padding-bottom: 6px;" />


                        </div>
                    </div>
                </div>




            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary btnDialogAddPhotobookClose pull-left">Закрыть</button>

                <button type="button" class="btn btn-primary btnDialogCreatePhotobook pull-right" data-url="<?php echo Url::toRoute(['photobook-api/create-photobook']); ?>">Создать книгу</button>
            </div>
        </div>
    </div>
</div>

<!--
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div>
        </div>

    </div>
</div>-->
