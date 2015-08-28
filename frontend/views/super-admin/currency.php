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

                <form class="form-horizontal" method="post">

                    <div class="row ">
                        <div class="col-xs-12  ">

                            <div class="">


                                <div class="form-group field-profileform-username required">
                                    <label class="control-label col-xs-6" for="profileform-username"><?php echo Yii::t('app', 'Курсы валют:'); ?></label>
                                    <div class="col-xs-6">



                                        <?php if(!empty($settings['currencies'])): ?>

                                            <?php $i=0; ?>

                                            <?php foreach($settings['currencies'] as $key=>$currency): ?>
                                            <div class="row">
                                                <div class="col-xs-5">
                                                    <input type="text" class="form-control col-xs-3 " name="SettingForm[currencies][<?php echo $i; ?>][code]" value="<?php echo $currency['code'] ?>" size="7">
                                                </div>
                                                <div class="col-xs-5">
                                                    <input type="text" class="form-control col-xs-3" name="SettingForm[currencies][<?php echo $i; ?>][value]" value="<?php if(!empty($currency['value'])) echo $currency['value'] ?>" size="7">
                                                </div>
                                                <div class="col-xs-2">
                                                    <a class="btn btn-primary btnMinusCurseRow"  ><i style="padding-right: 5px;" class="fa fa-minus"></i></a>
                                                </div>
                                                <?php $i++; ?>
                                            </div>
                                            <?php endforeach; ?>

                                        <?php endif; ?>

                                        <div class="cont">
                                            <div class="row"  >
                                                <div class="col-xs-5">
                                                    <input type="text" class="form-control code" placeholder="<?php echo Yii::t('app', 'Введите код валюты') ?>" name="code" value="" size="7">
                                                </div>
                                                <div class="col-xs-5">
                                                    <input type="number" class="form-control value" placeholder="<?php echo Yii::t('app', 'Введите курс') ?>" name="value" value="" size="7">
                                                </div>
                                                <div class="col-xs-2">
                                                    <a class="btn btn-primary btnAddCurseRow" data-index="1000" ><i style="padding-right: 5px;" class="fa fa-plus"></i></a>
                                                </div>
                                            </div>
                                        </div>



                                        <?php if(!empty($active_page['errors']) && !empty($active_page['errors']['currencies'])): ?>
                                            <div class="alert alert-danger ">
                                                <?php foreach($active_page['errors']['currencies'] as $key=>$error_msg): ?>
                                                    <?php echo str_replace("«Value»","",$error_msg); ?>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>


                                <div class="form-group field-profileform-username required">
                                    <label class="control-label col-xs-6" for="profileform-username"><?php echo Yii::t('app', 'Код главной валюты:'); ?></label>
                                    <div class="col-xs-6">
                                        <input type="text"  class="form-control " name="SettingForm[main_currency]" value="<?php echo $settings['main_currency'] ?>" size="7">
                                        <?php if(!empty($active_page['errors']) && !empty($active_page['errors']['main_currency'])): ?>
                                            <div class="alert alert-danger ">
                                                <?php foreach($active_page['errors']['main_currency'] as $key=>$error_msg): ?>
                                                    <?php echo str_replace("«Value»","",$error_msg); ?>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>


                                <div class="form-group field-profileform-username required">
                                    <label class="control-label col-xs-6" for="profileform-username"><?php echo Yii::t('app', 'Валюта по умолчанию:'); ?></label>
                                    <div class="col-xs-6">
                                        <input type="text"  class="form-control " name="SettingForm[default_currency]" value="<?php echo $settings['default_currency'] ?>" size="7">
                                        <?php if(!empty($active_page['errors']) && !empty($active_page['errors']['default_currency'])): ?>
                                            <div class="alert alert-danger ">
                                                <?php foreach($active_page['errors']['default_currency'] as $key=>$error_msg): ?>
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


