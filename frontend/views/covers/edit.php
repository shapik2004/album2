<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

use yii\helpers\Url;

use frontend\widgets\StyleLayoutGroup;

use frontend\assets\TemplatesEditAsset;
//TemplatesEditAsset::register($this);

use frontend\assets\CoverEditAsset;
use yii\widgets\ActiveForm;
use frontend\widgets\ThumbTemplateInGroup;

use app\components\UserUrl;

CoverEditAsset::register($this);


$this->title = Yii::t('app','Редактор обложки' );
$this->params['breadcrumbs'][] = ['label'=>Yii::t('app', 'Обложка'), 'url'=> Url::toRoute(['covers/index'])];
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
                                <input id="inputCoverName"  data-url="<?php echo  Url::toRoute(['covers-api/update',  'id'=>$cover->id, 'field_name'=>'name']); ?>"     class="form-control" type="text" placeholder="Введите имя обложки" value="<?=$cover->name; ?>">
                            </div>
                        </div>





                    </div>
                    <div class="col-md-1">



                        <div class="row">
                            <div class="col-md-12">
                                <?php if($cover->thumb=='style_default' || $cover->thumb=='default_style_thumb' || empty($cover->thumb)): ?>
                                    <img class="style-min-thumb pull-right" src="/images/style_default.jpg"/>
                                <?php else: ?>
                                    <img class="style-min-thumb pull-right" src="<?php echo UserUrl::coverThumb(true, $cover->id).'/'.UserUrl::imageFile($cover->thumb, UserUrl::IMAGE_THUMB) ?>"/>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <form enctype="multipart/form-data" action="<?php echo  Url::toRoute(['covers-api/upload-thumb', 'id'=>$cover->id]); ?>">
                                    <span   class="btn btn-link  button-1-line turn-on-editable pull-right fileinput-button">
                                        <!--<div class="button-col button-icon ">
                                            <i class="glyphicons picture"></i>
                                        </div>-->
                                        <div class="button-col">Изменить</div>
                                        <input type="file" data-url="<?php echo  Url::toRoute(['covers-api/upload-thumb', 'id'=>$cover->id]); ?>" data-base="<?php echo  Url::toRoute(['covers-api/upload-thumb', 'id'=>$cover->id]); ?>"  multiple="" value="" name="CoverForm[photo]" class="fileupload-thumb">
                                    </span>
                                </form>
                            </div>
                        </div>


                    </div>

                </div>





                <div class="form-group">
                    <div class="">
                        <label>
                            <?php  echo Yii::t('app', 'Операция цены'); ?>:

                            <?php echo Html::dropDownList('price_sign', [$cover->price_sign], $price_signs, ['id'=>'selectPriceSign', 'data-url'=>Url::toRoute(['covers-api/update',  'id'=>$cover->id, 'field_name'=>'price_sign'])]); ?>
                        </label>
                    </div>
                </div>



                <div class="form-group">

                    <div class="input-group">
                        <div class="input-group-addon"><?php echo Yii::t('app', 'Цена') ?></div>
                        <input id="inputCoverPrice"  data-url="<?php echo  Url::toRoute(['covers-api/update',  'id'=>$cover->id, 'field_name'=>'price']); ?>"     class="form-control" type="number" placeholder="Введите цену" value="<?=$cover->price; ?>">
                    </div>
                </div>


                <div class="form-group">
                    <div class="">
                        <label>
                            <?php  echo Yii::t('app', 'Тип материала'); ?>:

                            <?php echo Html::dropDownList('material_type', [$cover->material_type], $material_types, ['id'=>'selectMaterialType', 'data-url'=>Url::toRoute(['covers-api/update',  'id'=>$cover->id, 'field_name'=>'material_type'])]); ?>
                        </label>
                    </div>
                </div>




                <div class="form-group">

                    <form action="<?php echo Url::toRoute(['covers-api/upload-padded-cover',  'id'=>$cover->id]); ?>" enctype="multipart/form-data">
                        <span class="btn btn-default button-1-line fileinput-button ladda-button" data-style="zoom-in" data-spinner-color="#993149">

                            <div class="button-col button-icon ">
                                <i class="glyphicons picture"></i>
                            </div>
                            <div class="button-col">
                                <?php echo Yii::t('app', 'Загрузить подложку обложки'); ?>
                            </div>
                            <input type="file" class="fileupload-padded-cover" name="CoverForm[photo]" value="" data-url="<?php echo Url::toRoute(['covers-api/upload-padded-cover',  'id'=>$cover->id]); ?>">
                        </span>
                    </form>

                    <div id="paddedCoverPreview">

                        <?php if(file_exists(UserUrl::coverPadded(false, $cover->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($cover->padded_cover, UserUrl::IMAGE_THUMB, 'png'))): ?>
                            <div class="row"><div class="col-lg-3"><div class="thumbnail"><a class="thumbnail"><img src="<?php echo UserUrl::coverPadded(true, $cover->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($cover->padded_cover, UserUrl::IMAGE_THUMB, 'png'); ?>" /></a></div></div></div>
                        <?php endif; ?>
                    </div>
                </div>



                <div class="form-group">

                    <form action="<?php echo Url::toRoute(['covers-api/upload-cover-front',  'id'=>$cover->id]); ?>" enctype="multipart/form-data">
                            <span class="btn btn-default button-1-line fileinput-button ladda-button" data-style="zoom-in" data-spinner-color="#993149">

                                <div class="button-col button-icon ">
                                    <i class="glyphicons picture"></i>
                                </div>
                                <div class="button-col">
                                    <?php echo Yii::t('app', 'Загрузить переднюю обложку'); ?>
                                </div>
                                <input type="file" class="fileupload-cover-front" name="CoverForm[photo]" value="" data-url="<?php echo Url::toRoute(['covers-api/upload-cover-front',  'id'=>$cover->id]); ?>">
                            </span>
                    </form>

                    <div id="coverFrontPreview">

                        <?php if(file_exists(UserUrl::coverFront(false, $cover->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($cover->cover_front, UserUrl::IMAGE_THUMB, 'png'))): ?>
                            <div class="row"><div class="col-lg-3"><div class="thumbnail"><a class="thumbnail"><img src="<?php echo UserUrl::coverFront(true, $cover->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($cover->cover_front, UserUrl::IMAGE_THUMB, 'png'); ?>" /></a></div></div></div>
                        <?php endif; ?>
                    </div>
                </div>




                <div class="form-group">

                    <form action="<?php echo Url::toRoute(['covers-api/upload-cover-back',  'id'=>$cover->id]); ?>" enctype="multipart/form-data">
                            <span class="btn btn-default button-1-line fileinput-button ladda-button" data-style="zoom-in" data-spinner-color="#993149">

                                <div class="button-col button-icon ">
                                    <i class="glyphicons picture"></i>
                                </div>
                                <div class="button-col">
                                    <?php echo Yii::t('app', 'Загрузить заднюю обложку'); ?>
                                </div>
                                <input type="file" class="fileupload-cover-back" name="CoverForm[photo]" value="" data-url="<?php echo Url::toRoute(['covers-api/upload-cover-back',  'id'=>$cover->id]); ?>">
                            </span>
                    </form>

                    <div id="coverBackPreview">

                        <?php if(file_exists(UserUrl::coverBack(false, $cover->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($cover->cover_back, UserUrl::IMAGE_THUMB, 'png'))): ?>
                            <div class="row"><div class="col-lg-3"><div class="thumbnail"><a class="thumbnail"><img src="<?php echo UserUrl::coverBack(true, $cover->id ).DIRECTORY_SEPARATOR. UserUrl::imageFile($cover->cover_back, UserUrl::IMAGE_THUMB, 'png'); ?>" /></a></div></div></div>
                        <?php endif; ?>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6">

                        <div class="form-group">

                            <div class="input-group">
                                <div class="input-group-addon"><?php echo Yii::t('app', 'Смещение окна с названием книги по горизонтали') ?></div>
                                <input id="inputWindowOffsetX"  data-url="<?php echo  Url::toRoute(['covers-api/update',  'id'=>$cover->id, 'field_name'=>'window_offset_x']); ?>"     class="form-control" type="number" placeholder="Введите cмещение окна с названием книги по горизонтали (мм)" value="<?=$cover->window_offset_x; ?>">
                            </div>
                        </div>


                        <div class="form-group">

                            <div class="input-group">
                                <div class="input-group-addon"><?php echo Yii::t('app', 'Смещение окна с названием книги по вертикали') ?></div>
                                <input id="inputWindowOffsetY"  data-url="<?php echo  Url::toRoute(['covers-api/update',  'id'=>$cover->id, 'field_name'=>'window_offset_y']); ?>"     class="form-control" type="number" placeholder="Введите cмещение окна с названием книги по вертикали (мм)" value="<?=$cover->window_offset_y; ?>">
                            </div>
                        </div>






                    </div>
                    <div class="col-md-6">

                        <div class="form-group">

                            <div class="input-group">
                                <div class="input-group-addon"><?php echo Yii::t('app', 'Ширина окна с названием книги') ?></div>
                                <input id="inputWindowWidth"  data-url="<?php echo  Url::toRoute(['covers-api/update',  'id'=>$cover->id, 'field_name'=>'window_width']); ?>"     class="form-control" type="number" placeholder="Введите ширину окна с названием книги (мм)" value="<?=$cover->window_width; ?>">
                            </div>
                        </div>


                        <div class="form-group">

                            <div class="input-group">
                                <div class="input-group-addon"><?php echo Yii::t('app', 'Высота окна с названием книги') ?></div>
                                <input id="inputWindowHeight"  data-url="<?php echo  Url::toRoute(['covers-api/update',  'id'=>$cover->id, 'field_name'=>'window_height']); ?>"     class="form-control" type="number" placeholder="Введите высоту окна с названием книги (мм)" value="<?=$cover->window_height; ?>">
                            </div>
                        </div>


                    </div>

                </div>




                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input id="defaultCheckbox" type="checkbox" data-url="<?php echo  Url::toRoute(['covers-api/update',  'id'=>$cover->id, 'field_name'=>'default']); ?>"  <?php echo ($cover->default==1) ? 'checked': ''; ?>  value="1"> <?php echo Yii::t('app', 'По умолчанию') ?>
                        </label>
                    </div>
                </div>



                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input id="publishCheckbox" type="checkbox" data-url="<?php echo  Url::toRoute(['covers-api/update',  'id'=>$cover->id, 'field_name'=>'status']); ?>"  <?php echo ($cover->status==1) ? 'checked': ''; ?>  value="1"> <?php echo Yii::t('app', 'Опубликован') ?>
                        </label>
                    </div>
                </div>



                <br/>







                <a class="btn btn-primary button-1-line btnDeleteGroup " data-confirm="Вы уверены?"  href="<?php echo Url::toRoute(['covers/delete', 'id'=>$cover->id]); ?>" style="">
                    <div class="button-col button-icon ">
                        <i class="fa fa-trash"></i>
                    </div>
                    <div class="button-col">
                        <?php echo Yii::t('app', 'Удалить обложку'); ?>
                    </div>
                </a>

                <br/><br/>

            </div>
        </div>
    </div>





</div>

<div class="templates-select-area"
     data-gettemplatesurl="<?php echo Url::toRoute(['template-api/get-templates-by-ph-count']); ?>"
     data-settemplateidurl="<?php echo Url::toRoute(['covers-api/set-template-id', 'id'=>$cover->id]); ?>"
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
