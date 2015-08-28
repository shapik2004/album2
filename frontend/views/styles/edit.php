<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

use yii\helpers\Url;

use frontend\widgets\StyleLayoutGroup;

use frontend\assets\TemplatesEditAsset;
//TemplatesEditAsset::register($this);

use frontend\assets\StylesEditAsset;
use yii\widgets\ActiveForm;
use frontend\widgets\ThumbTemplateInGroup;

use app\components\UserUrl;

StylesEditAsset::register($this);


$this->title = Yii::t('app','Редактор стиля ' );
$this->params['breadcrumbs'][] = ['label'=>Yii::t('app', 'Стили'), 'url'=> Url::toRoute(['styles/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="styles-edit">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">

                <div class="row">
                    <div class="col-xs-12">
                        <h2 ><?= Html::encode($this->title); ?></h2>
                    </div>
                </div>
                <hr />
                <div class="upload-errors">

                </div>


                <div class="row">
                    <div class="col-md-11">

                        <div class="form-group">

                            <div class="input-group">
                                <div class="input-group-addon"><?php echo Yii::t('app', 'Имя') ?></div>
                                <input id="inputStyleName"  data-url="<?php echo  Url::toRoute(['styles-api/update',  'id'=>$style->id, 'field_name'=>'name']); ?>"     class="form-control" type="text" placeholder="Введите имя стиля" value="<?=$style->name; ?>">
                            </div>
                        </div>


                        <div class="form-group">

                            <div class="input-group">
                                <div class="input-group-addon"><?php echo Yii::t('app', 'Максимальное количество разворотов') ?></div>
                                <input id="inputStyleMaxSpread"  data-url="<?php echo  Url::toRoute(['styles-api/update',  'id'=>$style->id, 'field_name'=>'max_spread']); ?>"     class="form-control" type="number" placeholder="Введите максимальное количество разворотов" value="<?=$style->max_spread; ?>">
                            </div>
                        </div>



                        <div class="form-group">

                            <div class="input-group">
                                <div class="input-group-addon"><?php echo Yii::t('app', 'Цена за разворот') ?></div>
                                <input id="inputPrice"  data-url="<?php echo  Url::toRoute(['styles-api/update',  'id'=>$style->id, 'field_name'=>'price_spread']); ?>"     class="form-control" type="number" placeholder="Введите цену" value="<?=$style->price_spread; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><?php echo Yii::t('app', 'Текст для иконки') ?></div>
                                <input id="inputTextForIcon"  data-url="<?php echo  Url::toRoute(['styles-api/update',  'id'=>$style->id, 'field_name'=>'text_for_icon']); ?>"     class="form-control" type="text" placeholder="Введите текст для иконки" value="<?=$style->text_for_icon; ?>">
                            </div>
                        </div>

                    </div>
                    <div class="col-md-1">



                        <div class="row">
                            <div class="col-md-12">
                                <?php if($style->thumb_key=='style_default' || $style->thumb_key=='default_style_thumb' || empty($style->thumb_key)): ?>
                                    <img class="style-min-thumb pull-right" src="/images/style_default.jpg"/>
                                <?php else: ?>
                                    <img class="style-min-thumb pull-right" src="<?php echo UserUrl::styleThumb(true, $style->id).'/'.UserUrl::imageFile($style->thumb_key, UserUrl::IMAGE_THUMB) ?>"/>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <form enctype="multipart/form-data" action="<?php echo  Url::toRoute(['styles-api/upload-thumb', 'id'=>$style->id]); ?>">
                                    <span   class="btn btn-link  button-1-line turn-on-editable pull-right fileinput-button">
                                        <!--<div class="button-col button-icon ">
                                            <i class="glyphicons picture"></i>
                                        </div>-->
                                        <div class="button-col">Изменить</div>
                                        <input type="file" data-url="<?php echo  Url::toRoute(['styles-api/upload-thumb', 'id'=>$style->id]); ?>" data-base="<?php echo  Url::toRoute(['styles-api/upload-thumb', 'id'=>$style->id]); ?>"  multiple="" value="" name="StyleForm[photo]" class="fileupload-thumb">
                                    </span>
                                </form>
                            </div>
                        </div>


                    </div>

                </div>






                    <div class="form-group">
                        <form action="<?php echo Url::toRoute(['styles-api/upload-padded-passepartout',  'id'=>$style->id]); ?>" enctype="multipart/form-data">

                        <span class="btn btn-default button-1-line fileinput-button ladda-button" data-style="zoom-in" data-spinner-color="#993149">

                            <div class="button-col button-icon ">
                                <i class="glyphicons picture"></i>
                            </div>
                            <div class="button-col">
                                <?php echo Yii::t('app', 'Загрузить подложку паспарту разворота'); ?>
                            </div>
                            <input type="file" class="fileupload-padded-passepartout" name="StyleForm[photo]" value="" multiple=""  data-url="<?php echo Url::toRoute(['styles-api/upload-padded-passepartout',  'id'=>$style->id]); ?>">
                        </span>
                        </form>
                        <div id="paddedPassepartoutPreview">

                            <?php if(file_exists(UserUrl::stylePaddedPassepartout(false, $style->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($style->padded_passepartout_key, UserUrl::IMAGE_THUMB))): ?>
                            <div class="row"><div class="col-lg-3"><div class="thumbnail"><a class="thumbnail"><img src="<?php echo UserUrl::stylePaddedPassepartout(true, $style->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($style->padded_passepartout_key, UserUrl::IMAGE_THUMB); ?>" /></a></div></div></div>
                            <?php endif; ?>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="">
                            <label>
                                <?php  echo Yii::t('app', 'Цвет однопиксельной обводки для верхнего левого угла'); ?>:
                                <input data-url="<?php echo  Url::toRoute(['styles-api/update',  'id'=>$style->id, 'field_name'=>'placeholder_border_color_top_left']); ?>" type="text" class="color-picker form-control input-sm" name="placeholder_border_color_top_left" value="<?php echo $style->placeholder_border_color_top_left; ?>" style="width:80%"  maxlength="7" autocomplete="off">
                            </label>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="">
                            <label>
                                <?php  echo Yii::t('app', 'Цвет однопиксельной обводки для нижнего правого угла'); ?>:
                                <input data-url="<?php echo  Url::toRoute(['styles-api/update',  'id'=>$style->id, 'field_name'=>'placeholder_border_color_bottom_right']); ?>" type="text" class="color-picker form-control input-sm" name="placeholder_border_color_bottom_right" value="<?php echo $style->placeholder_border_color_bottom_right; ?>" style="width:80%"  maxlength="7" autocomplete="off">
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="">
                            <label>
                                <?php  echo Yii::t('app', 'Шрифт'); ?>:

                                <?php echo Html::dropDownList('font', [$style->font_id], $fonts, ['id'=>'selectFont', 'data-url'=>Url::toRoute(['styles-api/update',  'id'=>$style->id, 'field_name'=>'font_id'])]); ?>
                            </label>
                        </div>
                    </div>











                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input id="publishCheckbox" type="checkbox" data-url="<?php echo  Url::toRoute(['styles-api/update',  'id'=>$style->id, 'field_name'=>'status']); ?>"  <?php echo ($style->status==1) ? 'checked': ''; ?>  value="1"> <?php echo Yii::t('app', 'Опубликован') ?>
                            </label>
                        </div>
                    </div>





                    <!--

$output=Html::input('text', 'background_color', $this->background_color, ['class'=>'color-picker form-control input-sm', 'style'=>'width:80%',  'data-url'=>$this->change_background_color_url]);
return   Html::tag('div', '<label>Цвет фона: '.$output.'</label> ', ['class'=>'']);

1. Загружаем подложку паспарту разворота с черной тканью
2. Определить цвет однопиксельной обводки - для верхнего левого угла и нижнего правого
3. Загружаем подложку обложки
4. Определить шрифт
5. Определить максимальное количесво разворотов (20)
6. текст для иконки (Картон паспарту белый  Макс колво разворотов 20  )

                    -->






                <br/>







                <a class="btn btn-primary button-1-line btnDeleteGroup " data-confirm="Вы уверены?"  href="<?php echo Url::toRoute(['styles/delete', 'id'=>$style->id]); ?>" style="">
                    <div class="button-col button-icon ">
                        <i class="fa fa-trash"></i>
                    </div>
                    <div class="button-col">
                        <?php echo Yii::t('app', 'Удалить стиль'); ?>
                    </div>
                </a>

                <br/><br/>

            </div>
        </div>
    </div>





</div>

<div class="templates-select-area"
     data-gettemplatesurl="<?php echo Url::toRoute(['template-api/get-templates-by-ph-count']); ?>"
     data-settemplateidurl="<?php echo Url::toRoute(['styles-api/set-template-id', 'id'=>$style->id]); ?>"
    >

    <div><?php echo Yii::t('app', 'Выберите макет:') ?></div>
    <div class="templates"></div>
</div>

<div class="loader">

    <div class="place">
        <i class="anim glyphicons rotation_lock"></i><br/>

        <label><?php echo Yii::t('app', 'Поворачиваем...') ?></label>

    </div>
</div>
