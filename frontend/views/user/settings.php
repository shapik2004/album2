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

$this->registerJs('var pbColorTemplate="'.preg_replace('/^\s+|\n|\r|\s+$/m', ' ', $less_content).'";',  View::POS_HEAD);

$this->title = Yii::t('app','Настройки');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-settings">
    <h2><?= Html::encode($this->title) ?></h2>

    <p><?php echo Yii::t('app', 'На этой странице вы можите загрузить Ваш логотип и выбрать ваши фирменные цвета кнопок и ссылок.'); ?></p>

    <hr>

    <?php $form = ActiveForm::begin(['id' => 'form-settings',  'options' => ['class' => 'form-horizontal', 'enctype'=>'multipart/form-data']]); ?>




        <!-- http://blog.koalite.com/bbg/-->
        <div class="row ">
            <div class="col-xs-5  ">
                <h3><?php echo Yii::t('app', 'Ваш логотип'); ?></h3>
                <div class="">


                    <?= $form->field($model, 'logo_url')->begin(); ?>
                    <?= Html::activeLabel($model,'logo_url', [ 'label' => Yii::t('app', 'Выберите Ваш логотип:'), 'class'=>'control-label col-xs-6']); ?>

                        <span class="btn btn-default btn-file">
                        <?php echo Yii::t('app', 'Выбрать...') ?><?= Html::activeFileInput($model, 'logo_url', ['id'=>'logo_url', ]) ?>
                        </span>

                    <?= $form->field($model, 'logo_url')->end(); ?>

                    <div class="col-xs-6 col-xs-offset-6" >

                        <a href="#" class="btnLogoDefault"><?php echo Yii::t('app', 'По умолчанию') ?></a>
                    </div><br/><br/>



                    <h3><?php echo Yii::t('app', 'Фирменные цвета'); ?></h3>




                        <?= $form->field($model, 'color_1')->begin(); ?>
                        <?= Html::activeLabel($model,'color_1', [ 'label' => Yii::t('app', 'Цвет 1:'), 'class'=>'control-label col-xs-6']); ?>
                        <div class="col-xs-6">
                            <?= Html::activeTextInput($model, 'color_1', ['id'=>'color_1', 'class'=>'color-picker form-control input-sm', 'size'=>'7']) ?>
                        </div>
                        <?= $form->field($model, 'color_1')->end(); ?>


                        <?= $form->field($model, 'color_2')->begin(); ?>
                        <?= Html::activeLabel($model,'color_2', [ 'label' => Yii::t('app', 'Цвет 2:'), 'class'=>'control-label col-xs-6']); ?>
                        <div class="col-xs-6">
                            <?= Html::activeTextInput($model, 'color_2', ['id'=>'color_2', 'class'=>'color-picker form-control input-sm', 'size'=>'7']) ?>
                        </div>
                        <?= $form->field($model, 'color_2')->end(); ?>


                        <div class="col-xs-6 col-xs-offset-6" >

                                <a href="#" class="btnColorDefault"><?php echo Yii::t('app', 'По умолчанию') ?></a>
                        </div><br/><br/>

                        <div class="form-group">

                            <div class="col-xs-6 col-xs-offset-6" >
                                <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
                            </div>
                        </div>


                </div>
            </div>

            <div class="col-xs-7  " >

                <h3><?php echo Yii::t('app', 'Просмотр'); ?></h3>
                <div class="settings-preview1 ">
                    <div class="container-fluid">
                    <div class="row">
                        <nav role="navigation" class="navbar navbar-default" id="navbar">
                            <div class="container-fluid">
                                <!-- Brand and toggle get grouped for better mobile display -->
                                <div class="navbar-header">

                                    <a href="#" class="navbar-brand"><img class="logo_url_preview"  src="<?php echo UserUrl::logoUrl($model->logo_url, UserUrl::IMAGE_SMALL, 'png'); ?>" height="49"/></a>
                                </div>


                                <div class="navbar-form navbar-right">


                                    <div class="btn-group">

                                        <button type="button" class="btn btn-default button-2-line dropdown-toggle" data-toggle="dropdown">
                                            <div class="button-col button-icon ">
                                                <i class="glyphicons nameplate  "></i>
                                            </div>
                                            <div class="button-col">
                                                <?php echo Yii::t('app', 'Редактировать<br/>развороты'); ?>
                                            </div>
                                            <!--<div class="button-col button-caret">
                                                <i class="fa fa-chevron-down"></i>
                                            </div>-->
                                        </button>


                                        <ul role="menu" class="dropdown-menu">
                                            <li><a href="#myModal" data-toggle="modal">Сортировать развороты</a></li>

                                        </ul>
                                    </div>

                                    <div class="btn-group">

                                        <button type="button" class="btn btn-primary button-2-line ">
                                            <div class="button-col button-icon ">
                                                <i class="glyphicons  shopping_cart vectors "></i>
                                            </div>
                                            <div class="button-col">
                                                <?php echo Yii::t('app', 'Офирмить<br/>заказ'); ?>
                                            </div>
                                            <!--<div class="button-col button-caret">
                                                <i class="fa fa-chevron-down"></i>
                                            </div>-->
                                        </button>

                                    </div>





                                </div>



                            </div>
                        </nav>
                        <br/>
                        <a href="#">Обычная ссылка</a>
                    </div>

                    </div>
                </div>


            </div>
        </div>





        <!-- http://blog.koalite.com/bbg/-->
        <div class="row ">
            <div class="col-xs-5  ">

                <div class="">

                    <input id="defaultLogo" type="hidden" name="defaultLogo" value="0" />

                    <?= $form->field($model, 'default_text_color')->begin(); ?>

                    <div class="col-xs-6">
                        <?= Html::activeHiddenInput($model, 'default_text_color', ['id'=>'default_text_color', 'class'=>' form-control input-sm', 'size'=>'7']) ?>
                    </div>
                    <?= $form->field($model, 'default_text_color')->end(); ?>


                    <?= $form->field($model, 'default_back_color')->begin(); ?>

                    <div class="col-xs-6">
                        <?= Html::activeHiddenInput($model, 'default_back_color', ['id'=>'default_back_color', 'class'=>' form-control input-sm', 'size'=>'7']) ?>
                    </div>
                    <?= $form->field($model, 'default_back_color')->end(); ?>


                    <?= $form->field($model, 'default_border_color')->begin(); ?>

                    <div class="col-xs-6">
                        <?= Html::activeHiddenInput($model, 'default_border_color', ['id'=>'default_border_color', 'class'=>' form-control input-sm', 'size'=>'7']) ?>
                    </div>
                    <?= $form->field($model, 'default_border_color')->end(); ?>


                    <?= $form->field($model, 'default_active_text_color')->begin(); ?>

                    <div class="col-xs-6">
                        <?= Html::activeHiddenInput($model, 'default_active_text_color', ['id'=>'default_active_text_color', 'class'=>' form-control input-sm', 'size'=>'7']) ?>
                    </div>
                    <?= $form->field($model, 'default_active_text_color')->end(); ?>


                    <?= $form->field($model, 'default_active_back_color')->begin(); ?>

                    <div class="col-xs-6">
                        <?= Html::activeHiddenInput($model, 'default_active_back_color', ['id'=>'default_active_back_color', 'class'=>' form-control input-sm', 'size'=>'7']) ?>
                    </div>
                    <?= $form->field($model, 'default_active_back_color')->end(); ?>


                    <?= $form->field($model, 'default_active_border_color')->begin(); ?>

                    <div class="col-xs-6">
                        <?= Html::activeHiddenInput($model, 'default_active_border_color', ['id'=>'default_active_border_color', 'class'=>' form-control input-sm', 'size'=>'7']) ?>
                    </div>
                    <?= $form->field($model, 'default_active_border_color')->end(); ?>


                </div>
            </div>

            <div class="col-xs-6  ">


            </div>
        </div>

        <div class="row ">
            <div class="col-xs-5  ">

                <div class="">

                    <?= $form->field($model, 'primary_text_color')->begin(); ?>

                    <div class="col-xs-6">
                        <?= Html::activeHiddenInput($model, 'primary_text_color', ['id'=>'primary_text_color', 'class'=>'form-control input-sm', 'size'=>'7']) ?>
                    </div>
                    <?= $form->field($model, 'primary_text_color')->end(); ?>


                    <?= $form->field($model, 'primary_back_color')->begin(); ?>

                    <div class="col-xs-6">
                        <?= Html::activeHiddenInput($model, 'primary_back_color', ['id'=>'primary_back_color', 'class'=>'form-control input-sm', 'size'=>'7']) ?>
                    </div>
                    <?= $form->field($model, 'primary_back_color')->end(); ?>


                    <?= $form->field($model, 'primary_border_color')->begin(); ?>

                    <div class="col-xs-6">
                        <?= Html::activeHiddenInput($model, 'primary_border_color', ['id'=>'primary_border_color', 'class'=>'form-control input-sm', 'size'=>'7']) ?>
                    </div>
                    <?= $form->field($model, 'primary_border_color')->end(); ?>


                    <?= $form->field($model, 'primary_active_text_color')->begin(); ?>

                    <div class="col-xs-6">
                        <?= Html::activeHiddenInput($model, 'primary_active_text_color', ['id'=>'primary_active_text_color', 'class'=>'form-control input-sm', 'size'=>'7']) ?>
                    </div>
                    <?= $form->field($model, 'primary_active_text_color')->end(); ?>


                    <?= $form->field($model, 'primary_active_back_color')->begin(); ?>

                    <div class="col-xs-6">
                        <?= Html::activeHiddenInput($model, 'primary_active_back_color', ['id'=>'primary_active_back_color', 'class'=>'form-control input-sm', 'size'=>'7']) ?>
                    </div>
                    <?= $form->field($model, 'primary_active_back_color')->end(); ?>


                    <?= $form->field($model, 'primary_active_border_color')->begin(); ?>

                    <div class="col-xs-6">
                        <?= Html::activeHiddenInput($model, 'primary_active_border_color', ['id'=>'primary_active_border_color', 'class'=>' form-control input-sm', 'size'=>'7']) ?>
                    </div>
                    <?= $form->field($model, 'primary_active_border_color')->end(); ?>




                </div>

            </div>

            <div class="col-xs-6">


            </div>
        </div>


        <div class="row ">
            <div class="col-xs-5  ">

                <div class="">

                    <?= $form->field($model, 'link_color')->begin(); ?>

                    <div class="col-xs-6">
                        <?= Html::activeHiddenInput($model, 'link_color', ['id'=>'link_color', 'class'=>'form-control input-sm', 'size'=>'7']) ?>
                    </div>
                    <?= $form->field($model, 'link_color')->end(); ?>


                    <?= $form->field($model, 'active_link_color')->begin(); ?>

                    <div class="col-xs-6">
                        <?= Html::activeHiddenInput($model, 'active_link_color', ['id'=>'active_link_color', 'class'=>'form-control input-sm', 'size'=>'7']) ?>
                    </div>
                    <?= $form->field($model, 'active_link_color')->end(); ?>



                </div>

            </div>

            <div class="col-xs-6  ">


            </div>
        </div>

        <?= $form->field($model, 'css')->begin(); ?>
        <?= Html::activeHiddenInput($model, 'css', ['id'=>'css']) ?>
        <?= $form->field($model, 'css')->end(); ?>

    <?php ActiveForm::end(); ?>
</div>
