<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

use yii\helpers\Url;

use frontend\widgets\UploadPhotosGroup;

use frontend\assets\TemplatesEditAsset;

use app\components\UserUrl;


TemplatesEditAsset::register($this);

$this->title = Yii::t('app','Редактор макета');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="photobook-template-edit">




    <nav id="navbar" class="navbar navbar-default toolbar" role="navigation">

        <div class="container-fluid">

            <div class="row">
            <ul class="nav navbar-nav">

                <li>
                    <a class="btn btn-gray btn-tools" href="<?php  echo  Url::toRoute(['templates/index']);  ?>"><i class="glyphicon glyphicon-backward"></i></a>
                </li>
                <li></li>
                <li>
                    <span class="spanTemplateName"><?php echo $template->name; ?></span>
                </li>

                <li>
                    <span class="spanTemplateLastEdit"><?php echo $updated_ago; ?></span>
                </li>



            </ul>


            <ul class="nav navbar-nav pull-right" >
                <li class="pull-right">
                    <span>
                    <?php echo Yii::t('app', 'Единица измерения:'); ?>
                        <a href="#" class="btn btn-primary btn-xs btnSetUnit" data-value="px" role="button">пкс</a>
                    <a href="#" class="btn btn-primary btn-xs btnSetUnit active" data-value="mm" role="button">мм</a></span>

                </li>

                <li class="pull-right">
                    <input id="showGridCheckbox" type="checkbox"  checked value="option1"> <?php echo Yii::t('app', 'Показать сетку') ?>
                </li>

                <li class="pull-right">
                    <input id="snapToGridCheckbox" type="checkbox"  checked value="option1"> <?php echo Yii::t('app', 'Привязка к сетке') ?>
                </li>

            </ul>

            </div>



        </div>
    </nav>





            <div id="canvas-layout">
                <!--<canvas data-json='<?php echo $template->json; ?>' data-jsontext='<?php echo $template->json_text; ?>' data-url="<?php  echo  Url::toRoute(['template-api/save-changes',  'id'=>$template->id]); ?>" width="700" height="350" id="canvas_text" class="lower-canvas" style="left: 0px; top: 0px; -moz-user-select: none;"></canvas>-->
                <canvas  data-json='<?php echo $template->json; ?>' data-jsontext='<?php echo $template->json_text; ?>' data-url="<?php  echo  Url::toRoute(['template-api/save-changes',  'id'=>$template->id]); ?>" width="700" height="250" id="canvas_normal" class="lower-canvas" style="  left: 0px; top: 0px; -moz-user-select: none;"></canvas>
            </div>

            <div class="template-object-edit-panel" >



                    <div class="row">

                        <div class="col-lg-2 col-sm-2 col-xs-2">

                            <label><?php echo Yii::t('app', 'Размеры') ?></label><br/>

                            <div class="form-group">

                                <div class="input-group">
                                    <div class="input-group-addon" style="width: 50px; min-width: 50px;"><?php echo Yii::t('app', 'Ш:') ?></div>
                                    <input id="objectWidth"   class="form-control" type="number" placeholder="">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon" style="width: 50px;  min-width: 50px;"><?php echo Yii::t('app', 'В:') ?></div>
                                    <input id="objectHeight" class="form-control" type="number" placeholder="">
                                </div>
                            </div>



                        </div>



                        <div class="col-lg-2 col-sm-2 col-xs-2" >

                            <label><?php echo Yii::t('app', 'Позиция') ?></label><br/>

                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon" style="width: 50px; min-width: 50px;"><?php echo Yii::t('app', 'X') ?></div>
                                    <input id="objectX" class="form-control" type="number" placeholder="">
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon" style="width: 50px; min-width: 50px;"><?php echo Yii::t('app', 'Y') ?></div>
                                    <input id="objectY" class="form-control" type="number" placeholder="">
                                </div>
                            </div>

                        </div>



                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">

                            <div class="form-group" style="display: none;">
                                <div class="input-group">
                                    <div class="input-group-addon"><?php echo Yii::t('app', 'Толщина рамки') ?></div>
                                    <input id="objectStrokeWidth" class="form-control" type="number"   placeholder="">
                                </div>
                            </div>


                            <div class="form-group" style="display: none;">
                                <div class="input-group">
                                    <div class="input-group-addon"><?php echo Yii::t('app', 'Цвет рамки:') ?></div>
                                    <input type="text" size="7" value="#FFFFFF"  class="color-picker form-control input-sm" id="objectStrokeColor" maxlength="7" autocomplete="off">
                                </div>
                            </div>




                            <div class="row">
                                <div class="col-lg-12" style="padding-top: 25px;">
                                    <div class=" form-group" >

                                        <input type="radio" name="position" id="objectPosition" value="left_top">
                                        <input type="radio" name="position" id="objectPosition" value="center_top">
                                        <input type="radio" name="position" id="objectPosition" value="right_top">
                                    </div>


                                    <div class="form-group">
                                         <input type="radio" name="position" id="objectPosition" value="left_center">
                                         <input type="radio" name="position" id="objectPosition" value="center_center">
                                         <input type="radio" name="position" id="objectPosition" value="right_center">
                                    </div>


                                    <div class="form-group">
                                        <input type="radio" name="position" id="objectPosition" value="left_bottom">
                                        <input type="radio" name="position" id="objectPosition" value="center_bottom">
                                        <input type="radio" name="position" id="objectPosition" value="right_bottom">
                                    </div>
                                </div>

                                <div class="col-lg-6" style="display: none;" >
                                    <div class=" form-group" >
                                        <label><?php echo Yii::t('app', 'Рамка') ?></label><br/>
                                        <div class="text-center" style="padding-right: 20px;">
                                        <input type="checkbox" name="objectStrokePosition" class="stroke" value="border_top">
                                        </div>
                                    </div>

                                    <div class="form-group text-center" style="padding-right: 20px;">
                                        <input type="checkbox" name="objectStrokePosition" class="stroke" value="border_left">
                                        <input type="checkbox" name="objectStrokePosition" class="stroke" value="border_right">
                                    </div>

                                    <div class="form-group text-center" style="padding-right: 20px;">
                                        <input type="checkbox" name="objectStrokePosition" class="stroke" value="border_bottom">
                                    </div>
                                </div>


                            </div>

                            <div class="form-group">

                            </div>

                        </div>

                      <!--  <div class="form-group">
                            <div class="input-group">

                                <div class="checkbox">
                                    <label>
                                        <input id="objectPassepartout" type="checkbox" id="blankCheckbox" value="option1"> <?php echo Yii::t('app', 'Паспарту') ?>
                                    </label>
                                </div>

                            </div>
                        </div>-->

                        <div class="col-lg-2 col-sm-2 col-xs-2" style="padding-top: 18px;">

                            <div class="input-group">

                                <div class="checkbox">
                                    <label>
                                        <input id="objectMaybeAsText" type="checkbox"  checked value="option1"> <?php echo Yii::t('app', 'Может быть текстом') ?>
                                    </label>
                                </div>

                            </div>

                            <a class="btn btn-primary btnDelete pull-left"><?php echo Yii::t('app', 'Удалить'); ?></a>
                            <a class="btn btn-primary btnAdd pull-left"><?php echo Yii::t('app', 'Добавить объект'); ?></a>
                        </div>



                        <div class="col-lg-1 col-sm-2 col-xs-2" style="padding-top: 18px; border-left: solid 1px #ffffff;">
                            <!--<a class="btn btn-primary btnSelectBackground pull-left"><?php echo Yii::t('app', 'Выбрать фон...'); ?></a>-->

                            <form action="<?php echo Url::toRoute(['template-api/upload-fu2',  'id'=>$template->id, 'type'=>'1_L']); ?>" enctype="multipart/form-data">

                                <a class="btn btn-primary button-1-line fileinput-button btnUpload1L ladda-button" data-style="zoom-in" data-spinner-color="#993149">
                                    <!--<i class="glyphicon glyphicon-plus"></i>-->
                                    <div class="button-col button-icon ">
                                       <?php if(file_exists(UserUrl::fu2(false, $template->id, '1_L'))): ?>
                                           <i class="fa fa-check"></i>
                                        <?php else: ?>

                                           <i class="fa fa-remove"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="button-col" >
                                        <?php echo Yii::t('app', '1_L'); ?>
                                    </div>

                                    <input type="file" class="fileupload uploadInput1LFile" name="DynamicModel[fu2]" value="" multiple="" >


                                </a>
                            </form>

                            <br/>


                            <form action="<?php echo Url::toRoute(['template-api/upload-fu2',  'id'=>$template->id, 'type'=>'1_R']); ?>" enctype="multipart/form-data">
                                <a class="btn btn-primary button-1-line fileinput-button btnUpload1R ladda-button" data-style="zoom-in" data-spinner-color="#993149">
                                    <!--<i class="glyphicon glyphicon-plus"></i>-->
                                    <div class="button-col button-icon ">
                                        <?php if(file_exists(UserUrl::fu2(false, $template->id, '1_R'))): ?>
                                            <i class="fa fa-check"></i>
                                        <?php else: ?>

                                            <i class="fa fa-remove"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="button-col" >
                                        <?php echo Yii::t('app', '1_R'); ?>
                                    </div>

                                    <input type="file" class="fileupload uploadInput1RFile" name="DynamicModel[fu2]" value="" multiple="" >
                                </a>
                            </form>


                        </div>



                        <div class="col-lg-2 col-sm-3 col-xs-3" style="padding-top: 18px;">
                            <!--<a class="btn btn-primary btnSelectBackground pull-left"><?php echo Yii::t('app', 'Выбрать фон...'); ?></a>-->


                            <a class="btn btn-primary button-1-line fileinput-button btnSelectBackground ladda-button" data-style="zoom-in" data-spinner-color="#993149">
                                <!--<i class="glyphicon glyphicon-plus"></i>-->
                                <div class="button-col button-icon ">
                                    <i class="glyphicons picture"></i>
                                </div>
                                <div class="button-col" >
                                    <?php echo Yii::t('app', 'Выбрать фон...'); ?>
                                </div>

                                <input type="file" class="fileupload backgroundInputFile" name="PhotobookForm[photo]" value="" multiple="" >
                            </a>


                        </div>


                    </div>




<!--
                <br/><br/>
                <textarea id="myjson"></textarea>

                <button ng-click="addCircle()">s</button>-->

            </div>










</div>

<div id="templatePropertiesSideBar"   class="sidebar right-sidebar">

    <button type="button" class="btn btn-primary button-1-line btnToggleSettings sidebar-toggle-button">
        <div class="button-col button-icon ">
            <i class="glyphicons settings"></i>
        </div>

    </button>



    <div class="sidebar-content">

        <ul id="settingsTabBar" class="nav nav-tabs">
            <li role="presentation" class="active"><a href="#template-settings">Шаблон</a></li>
           <!-- <li role="presentation"><a href="#open-tamplate">Открыть...</a></li>-->
        </ul>


        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="template-settings">


                <form role="form" >



                        <div class="form-group">

                            <div class="input-group">
                                <div class="input-group-addon"><?php echo Yii::t('app', 'Имя') ?></div>
                                <input id="templateName"  data-url="<?php echo  Url::toRoute(['template-api/update-name',  'id'=>$template->id]); ?>"     class="form-control" type="text" placeholder="Введите имя шаблона" value="<?=$template->name; ?>">
                            </div>
                        </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input id="publishCheckbox" type="checkbox" data-url="<?php echo  Url::toRoute(['template-api/publish',  'id'=>$template->id]); ?>"  <?php echo $template->publish ? 'checked': ''; ?>  value="option1"> <?php echo Yii::t('app', 'Опубликован') ?>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                      <hr>
                    </div>


                    <div class="form-group">
                        <label>Количество фото:</label> <span class="spanCountPlaceholder"><?=$template->count_placeholder; ?></span>
                    </div>

                    <div class="form-group">
                        <label>Есть текст:</label> <span class="spanExistsText" data-yes="<?php echo Yii::t('app', 'Да') ?>"  data-no="<?php echo Yii::t('app', 'Нет') ?>" ><?php echo ($template->text_object) ? Yii::t('app', 'Да'):Yii::t('app', 'Нет'); ?></span>
                    </div>

                    <div class="form-group">
                        <label>Дата создания:</label> <?=date('d-m-Y H:i:s',$template->created_at); ?>
                    </div>

                    <div class="form-group">
                        <label>Дата изменения:</label> <span  class="spanUpdatedAt" ><?=date('d-m-Y H:i:s',$template->updated_at); ?></span>
                    </div>

                </form>


            </div>
           <!-- <div role="tabpanel" class="tab-pane" id="open-tamplate">2</div>-->

        </div>





    </div>



</div>



<div class="loader">

    <div class="place">
        <i class="anim glyphicons rotation_lock"></i><br/>
        <label><?php echo Yii::t('app', 'Поворачиваем...') ?></label>
    </div>
</div>

