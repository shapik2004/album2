<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use app\components\UserUrl;

/* @var $this yii\web\View */

use frontend\assets\SettingsAsset;
SettingsAsset::register($this);

//$this->jsFiles[]=Url::base().'/js/settings.js';



$this->title = Yii::t('app','Аккаунт');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-profile">
    <h2><?= Html::encode($this->title) ?></h2>

    <p><?php echo Yii::t('app', 'На этой странице вы можите изменить Ваше имя пользователя, email, пароль.'); ?></p>

    <hr>

    <?php $form = ActiveForm::begin(['id' => 'form-profile',  'options' => ['class' => 'form-horizontal']]); ?>

        <!-- http://blog.koalite.com/bbg/-->
        <div class="row ">
            <div class="col-xs-6  ">
                <!--<h3><?php echo Yii::t('app', 'Ваш логотип'); ?></h3>-->
                <div class="">

                    <?= $form->field($model, 'username')->begin(); ?>
                    <?= Html::activeLabel($model,'username', ['label' => Yii::t('app', 'Имя пользователя:'), 'class'=>'control-label col-xs-6']); ?>
                    <div class="col-xs-6">
                        <?= Html::activeTextInput($model, 'username', ['id'=>'username', 'class'=>'form-control ', 'size'=>'7']) ?>
                    </div>
                    <?= $form->field($model, 'username')->end(); ?>


                    <?= $form->field($model, 'email')->begin(); ?>
                    <?= Html::activeLabel($model,'email', ['label' => Yii::t('app', 'Email:'), 'class'=>'control-label col-xs-6']); ?>
                    <div class="col-xs-6">
                        <?= Html::activeTextInput($model, 'email', ['id'=>'email', 'class'=>'form-control ', 'size'=>'7']) ?>
                    </div>
                    <?= $form->field($model, 'email')->end(); ?>


                    <?= $form->field($model, 'password')->begin(); ?>
                    <?= Html::activeLabel($model,'password', ['label' => Yii::t('app', 'Пароль:'), 'class'=>'control-label col-xs-6']); ?>
                    <div class="col-xs-6">
                        <?= Html::activePasswordInput($model, 'password', ['id'=>'password', 'class'=>'form-control ', 'size'=>'7']) ?>
                    </div>
                    <?= $form->field($model, 'password')->end(); ?>


                    <?= $form->field($model, 'default_currency')->begin(); ?>
                    <?= Html::activeLabel($model,'default_currency', ['label' => Yii::t('app', 'Валюта по умолчанию:'), 'class'=>'control-label col-xs-6']); ?>
                    <div class="col-xs-6">
                        <?= Html::activeDropDownList($model, 'default_currency', $currencies,['id'=>'default_currency', 'class'=>'form-control ']) ?>
                    </div>
                    <?= $form->field($model, 'default_currency')->end(); ?>


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






    <?php ActiveForm::end(); ?>
</div>
