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
                                        <label ><?php echo Yii::t('app', 'Публичный ключ LiqPay:'); ?></label>
                                        <input  class="form-control " name="SettingForm[liqpay_public_key]" value="<?php echo $settings['liqpay_public_key'] ?>" />


                                        <?php if(!empty($active_page['errors']) && !empty($active_page['errors']['liqpay_public_key'])): ?>
                                            <div class="alert alert-danger ">
                                                <?php foreach($active_page['errors']['liqpay_public_key'] as $key=>$error_msg): ?>
                                                    <?php echo str_replace("«Value»","",$error_msg); ?>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>


                                <div class="form-group ">

                                    <div class="col-xs-12">
                                        <label ><?php echo Yii::t('app', 'Приватный ключ LiqPay:'); ?></label>
                                        <input  class="form-control " name="SettingForm[liqpay_private_key]" value="<?php echo $settings['liqpay_private_key'] ?>" />


                                        <?php if(!empty($active_page['errors']) && !empty($active_page['errors']['liqpay_private_key'])): ?>
                                            <div class="alert alert-danger ">
                                                <?php foreach($active_page['errors']['liqpay_private_key'] as $key=>$error_msg): ?>
                                                    <?php echo str_replace("«Value»","",$error_msg); ?>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>






                                <div class="row">

                                    <div class="col-xs-12" >

                                        <div class="form-group" style="padding-top: 10px;">


                                            <div class="col-xs-12" >
                                                <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
                                            </div>
                                        </div>
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


