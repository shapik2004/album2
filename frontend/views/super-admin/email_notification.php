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


use frontend\assets\SuperAdminSettingsAsset;
SuperAdminSettingsAsset::register($this);


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

                <form class="" method="post">

                    <div class="row ">
                        <div class="col-xs-12  ">

                            <div class="">


                                <!--

                                 'manager_notification_change_status',
                    'manager_notification_new_user',
                    'user_notification_change_status',
                    'user_notification_invoice_link',
                    'user_notification_payment_received',
                    'customer_notification_link_for_comments',
                                -->



                                <div class="form-group ">

                                    <div class="col-xs-12">
                                        <label ><?php echo Yii::t('app', 'Шаблон уведомления менеджера об изменении статуса:'); ?></label>
                                        <textarea  class="form-control " rows="10" name="SettingForm[manager_notification_change_status]" ><?php echo $settings['manager_notification_change_status'] ?></textarea>

                                        <div class="help-block">
                                            <?php echo Yii::t('app', 'Можно использывать теги и вставки с переменными данными.') ?><br/>
                                            {{manager_name}} - <?php echo Yii::t('app', 'Имя менеджера') ?><br/>
                                            {{photobook_name}} - <?php echo Yii::t('app', 'Название книги') ?><br/>
                                            {{photobook_link}} - <?php echo Yii::t('app', 'Ссылка на фотокнигу') ?><br/>
                                            {{user_name}} - <?php echo Yii::t('app', 'Имя фотографа') ?><br/>
                                            {{user_email}} - <?php echo Yii::t('app', 'Email фотографа') ?><br/>
                                            {{user_link}} - <?php echo Yii::t('app', 'Ссылка на профайл клиента') ?><br/>
                                            {{new_status}} - <?php echo Yii::t('app', 'Статус') ?><br/>
                                        </div>

                                        <?php if(!empty($active_page['errors']) && !empty($active_page['errors']['manager_notification_change_status'])): ?>
                                            <div class="alert alert-danger ">
                                                <?php foreach($active_page['errors']['manager_notification_change_status'] as $key=>$error_msg): ?>
                                                    <?php echo str_replace("«Value»","",$error_msg); ?>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>


                                <div class="form-group ">

                                    <div class="col-xs-12">
                                        <label ><?php echo Yii::t('app', 'Шаблон уведомления менеджера о новой регистрации:'); ?></label>
                                        <textarea  class="form-control " rows="10" name="SettingForm[manager_notification_new_user]" ><?php echo $settings['manager_notification_new_user'] ?></textarea>

                                        <div class="help-block">
                                            <?php echo Yii::t('app', 'Можно использывать теги и вставки с переменными данными.') ?><br/>
                                            {{manager_name}} - <?php echo Yii::t('app', 'Имя менеджера') ?><br/>
                                            {{user_name}} - <?php echo Yii::t('app', 'Имя фотографа') ?><br/>
                                            {{user_email}} - <?php echo Yii::t('app', 'Email фотографа') ?><br/>
                                            {{user_link}} - <?php echo Yii::t('app', 'Ссылка на профайл клиента') ?><br/>
                                            {{site_name}} - <?php echo Yii::t('app', 'Название сайта') ?><br/>
                                        </div>

                                        <?php if(!empty($active_page['errors']) && !empty($active_page['errors']['manager_notification_new_user'])): ?>
                                            <div class="alert alert-danger ">
                                                <?php foreach($active_page['errors']['manager_notification_new_user'] as $key=>$error_msg): ?>
                                                    <?php echo str_replace("«Value»","",$error_msg); ?>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>


                                <div class="form-group ">

                                    <div class="col-xs-12">
                                        <label ><?php echo Yii::t('app', 'Шаблон уведомления пользователя об изменении статуса:'); ?></label>
                                        <textarea  class="form-control " rows="10" name="SettingForm[user_notification_change_status]" ><?php echo $settings['user_notification_change_status'] ?></textarea>

                                        <div class="help-block">
                                            <?php echo Yii::t('app', 'Можно использывать теги и вставки с переменными данными.') ?><br/>
                                            {{photobook_name}} - <?php echo Yii::t('app', 'Название книги') ?><br/>
                                            {{photobook_link}} - <?php echo Yii::t('app', 'Ссылка на фотокнигу') ?><br/>
                                            {{user_name}} - <?php echo Yii::t('app', 'Имя фотографа') ?><br/>
                                            {{user_email}} - <?php echo Yii::t('app', 'Email фотографа') ?><br/>
                                            {{new_status}} - <?php echo Yii::t('app', 'Статус') ?><br/>
                                        </div>

                                        <?php if(!empty($active_page['errors']) && !empty($active_page['errors']['user_notification_change_status'])): ?>
                                            <div class="alert alert-danger ">
                                                <?php foreach($active_page['errors']['user_notification_change_status'] as $key=>$error_msg): ?>
                                                    <?php echo str_replace("«Value»","",$error_msg); ?>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>




                                <div class="form-group ">

                                    <div class="col-xs-12">
                                        <label ><?php echo Yii::t('app', 'Шаблон уведомления пользователя со ссылкой на счет для оплаты:'); ?></label>
                                        <textarea  class="form-control " rows="10" name="SettingForm[user_notification_invoice_link]" ><?php echo $settings['user_notification_invoice_link'] ?></textarea>

                                        <div class="help-block">
                                            <?php echo Yii::t('app', 'Можно использывать теги и вставки с переменными данными.') ?><br/>
                                            {{photobook_name}} - <?php echo Yii::t('app', 'Название книги') ?><br/>
                                            {{photobook_link}} - <?php echo Yii::t('app', 'Ссылка на фотокнигу') ?><br/>
                                            {{invoice_link}} - <?php echo Yii::t('app', 'Ссылка на счет') ?><br/>
                                            {{user_name}} - <?php echo Yii::t('app', 'Имя фотографа') ?><br/>
                                            {{user_email}} - <?php echo Yii::t('app', 'Email фотографа') ?><br/>
                                            {{new_status}} - <?php echo Yii::t('app', 'Статус') ?><br/>
                                        </div>

                                        <?php if(!empty($active_page['errors']) && !empty($active_page['errors']['user_notification_invoice_link'])): ?>
                                            <div class="alert alert-danger ">
                                                <?php foreach($active_page['errors']['user_notification_invoice_link'] as $key=>$error_msg): ?>
                                                    <?php echo str_replace("«Value»","",$error_msg); ?>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>



                                <div class="form-group ">

                                    <div class="col-xs-12">
                                        <label ><?php echo Yii::t('app', 'Шаблон уведомления пользователя о полученной оплате:'); ?></label>
                                        <textarea  class="form-control " rows="10" name="SettingForm[user_notification_payment_received]" ><?php echo $settings['user_notification_payment_received'] ?></textarea>

                                        <div class="help-block">
                                            <?php echo Yii::t('app', 'Можно использывать теги и вставки с переменными данными.') ?><br/>
                                            {{photobook_name}} - <?php echo Yii::t('app', 'Название книги') ?><br/>
                                            {{photobook_link}} - <?php echo Yii::t('app', 'Ссылка на фотокнигу') ?><br/>
                                            {{invoice_link}} - <?php echo Yii::t('app', 'Ссылка на счет') ?><br/>
                                            {{user_name}} - <?php echo Yii::t('app', 'Имя фотографа') ?><br/>
                                            {{user_email}} - <?php echo Yii::t('app', 'Email фотографа') ?><br/>
                                            {{new_status}} - <?php echo Yii::t('app', 'Статус') ?><br/>
                                        </div>

                                        <?php if(!empty($active_page['errors']) && !empty($active_page['errors']['user_notification_payment_received'])): ?>
                                            <div class="alert alert-danger ">
                                                <?php foreach($active_page['errors']['user_notification_payment_received'] as $key=>$error_msg): ?>
                                                    <?php echo str_replace("«Value»","",$error_msg); ?>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>



                                <div class="form-group ">

                                    <div class="col-xs-12">
                                        <label ><?php echo Yii::t('app', 'Шаблон уведомления пользователя о получении ссылки на согласование:'); ?></label>
                                        <textarea  class="form-control " rows="10" name="SettingForm[customer_notification_link_for_comments]" ><?php echo $settings['customer_notification_link_for_comments'] ?></textarea>

                                        <div class="help-block">
                                            <?php echo Yii::t('app', 'Можно использывать теги и вставки с переменными данными.') ?><br/>
                                            {{photobook_name}} - <?php echo Yii::t('app', 'Название книги') ?><br/>
                                            {{link_for_comments}} - <?php echo Yii::t('app', 'Ссылка на согласование') ?><br/>
                                            {{user_name}} - <?php echo Yii::t('app', 'Имя фотографа') ?><br/>
                                            {{user_email}} - <?php echo Yii::t('app', 'Email фотографа') ?><br/>
                                            {{new_status}} - <?php echo Yii::t('app', 'Статус') ?><br/>
                                        </div>

                                        <?php if(!empty($active_page['errors']) && !empty($active_page['errors']['customer_notification_link_for_comments'])): ?>
                                            <div class="alert alert-danger ">
                                                <?php foreach($active_page['errors']['customer_notification_link_for_comments'] as $key=>$error_msg): ?>
                                                    <?php echo str_replace("«Value»","",$error_msg); ?>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>





                                <div class="form-group">

                                    <div class="col-xs-12" >
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


