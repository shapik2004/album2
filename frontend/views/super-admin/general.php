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

$this->title = $active_page['title'];
$this->params['breadcrumbs'][]= Yii::t('app', 'Настройки системы');
$this->params['breadcrumbs'][] = $this->title;

/*echo $work_orders[0]->name;
print_r($work_orders);*/


use frontend\assets\PhotobooksIndexAsset;
PhotobooksIndexAsset::register($this);


?>


<div class="photobook-index">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">


            </div>
        </div>



        <div  class="row">
            <div class="col-xs-3" style="padding-top: 17px;">
                <div class="list-group">
                    <?php foreach($sidemenus as $key=>$menuitem): ?>

                        <a href="<?php echo $menuitem['url']; ?>" class="list-group-item <?php if($menuitem['active']) echo 'active'; ?>">

                            <?php echo $menuitem['title'] ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-xs-9">


                <div class="row">
                    <div class="col-xs-12">
                        <h2 ><?= Html::encode($this->title); ?></h2>
                    </div>
                </div>
                <hr />

                <form class="form-horizontal" method="post">

                <div class="row ">
                    <div class="col-xs-12  ">

                        <div class="">


                            <div class="form-group field-profileform-username required">
                                <label class="control-label col-xs-6" for="profileform-username"><?php echo Yii::t('app', 'Название сайта:'); ?></label>
                                <div class="col-xs-6">
                                    <input type="text" id="username" class="form-control " name="SettingForm[site_name]" value="<?php echo $settings['site_name'] ?>" size="7">

                                    <?php if(!empty($active_page['errors']) && !empty($active_page['errors']['site_name'])): ?>
                                        <div class="alert alert-danger ">
                                            <?php foreach($active_page['errors']['site_name'] as $key=>$error_msg): ?>
                                                <?php echo str_replace("«Value»","",$error_msg); ?>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>


                            <div class="form-group field-profileform-username required">
                                <label class="control-label col-xs-6" for="profileform-username"><?php echo Yii::t('app', 'ИД пользователя, из которого берутся демо фотокниги:'); ?></label>
                                <div class="col-xs-6">
                                    <input type="text" id="username" class="form-control " name="SettingForm[demo_account_id]" value="<?php echo $settings['demo_account_id'] ?>" size="7">

                                    <?php if(!empty($active_page['errors']) && !empty($active_page['errors']['demo_account_id'])): ?>
                                        <div class="alert alert-danger ">
                                            <?php foreach($active_page['errors']['demo_account_id'] as $key=>$error_msg): ?>
                                                <?php echo str_replace("«Value»","",$error_msg); ?>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>

                            <div class="form-group field-profileform-username required">
                                <label class="control-label col-xs-6" for="profileform-username"><?php echo Yii::t('app', 'Email для оповещений менеджера:'); ?></label>
                                <div class="col-xs-6">
                                    <input type="text" id="username" class="form-control " name="SettingForm[manager_notification_email]" value="<?php echo $settings['manager_notification_email'] ?>" size="7">
                                    <?php if(!empty($active_page['errors']) && !empty($active_page['errors']['manager_notification_email'])): ?>
                                        <div class="alert alert-danger ">
                                            <?php foreach($active_page['errors']['manager_notification_email'] as $key=>$error_msg): ?>
                                            <?php echo str_replace("«Value»","",$error_msg); ?>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>




                            <div class="form-group">



                                    <label class="control-label col-xs-6">
                                        <?php echo Yii::t('app', 'Оповещать менеджера при изменения статуса проекта на:'); ?>
                                    </label>



                                <div class=" col-xs-6">
                                    <div class="checkbox">
                                        <label>
                                            <input type="hidden"   name="SettingForm[manager_notification_status-<?php echo Photobook::STATUS_NEW ?>]"   value="0" size="7">
                                            <input type="checkbox"   name="SettingForm[manager_notification_status-<?php echo Photobook::STATUS_NEW ?>]"  <?php if($settings['manager_notification_status-'.Photobook::STATUS_NEW]) echo 'checked'; ?> value="1" size="7">

                                            <?php echo Yii::t('app', 'Новый проект'); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">

                                <div class="col-xs-offset-6 col-xs-6">
                                    <div class="checkbox">
                                        <label>
                                            <input type="hidden"   name="SettingForm[manager_notification_status-<?php echo Photobook::STATUS_SENT_TO_CUSTOMER ?>]"   value="0" size="7">
                                            <input type="checkbox"   name="SettingForm[manager_notification_status-<?php echo Photobook::STATUS_SENT_TO_CUSTOMER ?>]"  <?php if($settings['manager_notification_status-'.Photobook::STATUS_SENT_TO_CUSTOMER]) echo 'checked'; ?> value="1" size="7">

                                            <?php echo Yii::t('app', 'Отправлено на согласование'); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">

                                <div class="col-xs-offset-6 col-xs-6">
                                    <div class="checkbox">
                                        <label>
                                            <input type="hidden"   name="SettingForm[manager_notification_status-<?php echo Photobook::STATUS_WAIT_EDIT_FROM_CUSTOMER ?>]"   value="0" size="7">
                                            <input type="checkbox"   name="SettingForm[manager_notification_status-<?php echo Photobook::STATUS_WAIT_EDIT_FROM_CUSTOMER ?>]"  <?php if($settings['manager_notification_status-'.Photobook::STATUS_WAIT_EDIT_FROM_CUSTOMER]) echo 'checked'; ?> value="1" size="7">

                                            <?php echo Yii::t('app', 'Ожидает правок фотографа'); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">

                                <div class="col-xs-offset-6 col-xs-6">
                                    <div class="checkbox">
                                        <label>
                                            <input type="hidden"   name="SettingForm[manager_notification_status-<?php echo Photobook::STATUS_SENT_TO_PRINT ?>]"   value="0" size="7">
                                            <input type="checkbox"   name="SettingForm[manager_notification_status-<?php echo Photobook::STATUS_SENT_TO_PRINT ?>]"  <?php if($settings['manager_notification_status-'.Photobook::STATUS_SENT_TO_PRINT]) echo 'checked'; ?> value="1" size="7">

                                            <?php echo Yii::t('app', 'Утверждено клиентом'); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">

                                <div class="col-xs-offset-6 col-xs-6">
                                    <div class="checkbox">
                                        <label>
                                            <input type="hidden"   name="SettingForm[manager_notification_status-<?php echo Photobook::STATUS_READY_FOR_PRINT_WAIT_PAYMENT ?>]"   value="0" size="7">
                                            <input type="checkbox"   name="SettingForm[manager_notification_status-<?php echo Photobook::STATUS_READY_FOR_PRINT_WAIT_PAYMENT ?>]"  <?php if($settings['manager_notification_status-'.Photobook::STATUS_READY_FOR_PRINT_WAIT_PAYMENT]) echo 'checked'; ?> value="1" size="7">

                                            <?php echo Yii::t('app', 'Готово к печати, ожидает оплаты'); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">

                                <div class="col-xs-offset-6 col-xs-6">
                                    <div class="checkbox">
                                        <label>
                                            <input type="hidden"   name="SettingForm[manager_notification_status-<?php echo Photobook::STATUS_READY_FOR_PRINT_PAID ?>]"   value="0" size="7">
                                            <input type="checkbox"   name="SettingForm[manager_notification_status-<?php echo Photobook::STATUS_READY_FOR_PRINT_PAID ?>]"  <?php if($settings['manager_notification_status-'.Photobook::STATUS_READY_FOR_PRINT_PAID]) echo 'checked'; ?> value="1" size="7">

                                            <?php echo Yii::t('app', 'Готово к печати, оплачен'); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">

                                <div class="col-xs-offset-6 col-xs-6">
                                    <div class="checkbox">
                                        <label>
                                            <input type="hidden"   name="SettingForm[manager_notification_status-<?php echo Photobook::STATUS_READY_SENT_TO_PRINT ?>]"   value="0" size="7">
                                            <input type="checkbox"   name="SettingForm[manager_notification_status-<?php echo Photobook::STATUS_READY_SENT_TO_PRINT ?>]"  <?php if($settings['manager_notification_status-'.Photobook::STATUS_READY_SENT_TO_PRINT]) echo 'checked'; ?> value="1" size="7">

                                            <?php echo Yii::t('app', 'В производстве'); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">

                                <div class="col-xs-offset-6 col-xs-6">
                                    <div class="checkbox">
                                        <label>
                                            <input type="hidden"   name="SettingForm[manager_notification_status-<?php echo Photobook::STATUS_READY ?>]"   value="0" size="7">
                                            <input type="checkbox"   name="SettingForm[manager_notification_status-<?php echo Photobook::STATUS_READY ?>]"  <?php if($settings['manager_notification_status-'.Photobook::STATUS_READY]) echo 'checked'; ?> value="1" size="7">

                                            <?php echo Yii::t('app', 'Готово'); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">

                                <div class="col-xs-offset-6 col-xs-6">
                                    <div class="checkbox">
                                        <label>
                                            <input type="hidden"   name="SettingForm[manager_notification_status-<?php echo Photobook::STATUS_READY_SENT_TO_CLIENT ?>]"   value="0" size="7">
                                            <input type="checkbox"   name="SettingForm[manager_notification_status-<?php echo Photobook::STATUS_READY_SENT_TO_CLIENT ?>]"  <?php if($settings['manager_notification_status-'.Photobook::STATUS_READY_SENT_TO_CLIENT]) echo 'checked'; ?> value="1" size="7">

                                            <?php echo Yii::t('app', 'Отправлено'); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">

                                <div class="col-xs-offset-6 col-xs-6">
                                    <div class="checkbox">
                                        <label>
                                            <input type="hidden"   name="SettingForm[manager_notification_status-<?php echo Photobook::STATUS_RECEIVED_FEEDBACK ?>]"   value="0" size="7">
                                            <input type="checkbox"   name="SettingForm[manager_notification_status-<?php echo Photobook::STATUS_RECEIVED_FEEDBACK ?>]"  <?php if($settings['manager_notification_status-'.Photobook::STATUS_RECEIVED_FEEDBACK]) echo 'checked'; ?> value="1" size="7">

                                            <?php echo Yii::t('app', 'Доставлено'); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">

                                <div class="col-xs-offset-6 col-xs-6">
                                    <div class="checkbox">
                                        <label>
                                            <input type="hidden"   name="SettingForm[manager_notification_status-<?php echo Photobook::STATUS_ARCHIVE ?>]"   value="0" size="7">
                                            <input type="checkbox"   name="SettingForm[manager_notification_status-<?php echo Photobook::STATUS_ARCHIVE ?>]"  <?php if($settings['manager_notification_status-'.Photobook::STATUS_ARCHIVE]) echo 'checked'; ?> value="1" size="7">

                                            <?php echo Yii::t('app', 'Архив'); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">



                                <label class="control-label col-xs-6">
                                    <?php echo Yii::t('app', 'Политика генераци иконок для фотокниги:'); ?>
                                </label>



                                <div class=" col-xs-6">
                                    <div class="checkbox">
                                        <label>
                                            <input type="hidden"   name="SettingForm[photobook_thumb_as_object]"   value="0" size="7">
                                            <input type="checkbox"   name="SettingForm[photobook_thumb_as_object]"  <?php if($settings['photobook_thumb_as_object']) echo 'checked'; ?> value="1" size="7">

                                            <?php echo Yii::t('app', 'Как "object"'); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group ">

                                <div class="col-xs-12">
                                    <label ><?php echo Yii::t('app', 'Текст примечания на странице загрузки фотографий:'); ?></label>
                                    <textarea  class="form-control " rows="6" name="SettingForm[note_upload_page]" ><?php echo $settings['note_upload_page'] ?></textarea>

                                    <div class="help-block">
                                        <?php echo Yii::t('app', 'Можно использывать теги.') ?><br/>

                                    </div>

                                    <?php if(!empty($active_page['errors']) && !empty($active_page['errors']['note_upload_page'])): ?>
                                        <div class="alert alert-danger ">
                                            <?php foreach($active_page['errors']['note_upload_page'] as $key=>$error_msg): ?>
                                                <?php echo str_replace("«Value»","",$error_msg); ?>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-group">

                                <div class="col-xs-6 col-xs-offset-6">
                                    <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-xs-6  " >




                    </div>
                </div>






              </form>






            </div>
        </div>
    </div>





</div>


