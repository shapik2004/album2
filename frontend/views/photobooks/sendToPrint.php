<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

use yii\helpers\Url;

use app\components\UserUrl;



use frontend\assets\CheckoutAsset;
CheckoutAsset::register($this);

$this->title = Yii::t('app','Оформление заказа');
$this->params['breadcrumbs'][] = $this->title;
?>


<nav id="navbar" class="navbar navbar-default" role="navigation">

    <div class="container-fluid ">




        <div class="row">
            <div class="col-lg-5  col-md-3 ">
                <div class="navbar-header">
                    <a class="navbar-brand" >Оформление заказа <b>Шаг 1.</b></a>
                </div>
            </div>
            <div class="col-lg-7 col-md-9  header-buttons">


                <div class="navbar-form navbar-right" >


                    <div class="btn-group">

                       <!-- <a class="btn btn-gray button-2-line btnMakeLayoutsZip" data-url="<?php echo Url::toRoute(['photobook-api/download-layouts', 'id'=>$id, 'ref'=>$ref]); ?>" >
                            <div class="button-col button-icon ">
                                <i class="glyphicons compressed"></i>
                            </div>
                            <div class="button-col">
                                <?php echo Yii::t('app', 'Скачать zip-архив <br/>с макетами '); ?>
                            </div>


                        </a>-->

                    </div>
                </div>
            </div>
        </div>

    </div>
</nav>





<div class="row">
    <div class="col-xs-12">

        <div class="upload-errors">

        </div>
    </div>
</div>


<div class="row">
    <div class="col-xs-12">



        <!--<div class="alert alert-info"  role="alert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <p><span class="brand-color">Примечание. </span>Это техническая страница созданая для примера.</p>
            <p class="gray">Тут можно скачть макеты, в реальной ситуации макеты доступны только в админке!
            </p>
        </div>-->


        <h4 style="text-transform: uppercase; color: #555555;"><?php echo Yii::t('app', 'Название:') ?> <?php echo $model->name; ?></h4>

        <h4 style="text-transform: uppercase;  color: #555555;"><?php echo Yii::t('app', 'Обложка:').' '.$cover->material_type.'/'.$cover->name; ?></h4>

        <h4 style="text-transform: uppercase; color: #555555;"><?php echo Yii::t('app', 'Развороты:') ?></h4>

        <div class="row pages-area" data-ref="<?php echo $ref; ?>" data-id="<?php echo $id; ?>" style="margin-right: -45px; margin-left: -45px;">
            <?php foreach($pages as $key=>$page): ?>

                <?php   if($key==0 || $key==count($pages)-1)  continue; ?>
                <div class="col-xs-3 col-md-3 div-add-photo-placeholder"  data-index="<?php echo $key; ?>" >
                    <div data-index="<?php echo $key; ?>" class="subdiv" style="position: relative; width: 100%; right: auto; height: 100%; bottom: auto; left: 0px; top: 0px;" >
                        <a href="#" class="thumbnail svg-thumb svg-thumb-<?php echo $key; ?> " data-index="<?php echo $key; ?>">
                            <img width="100%" data-src="<?php echo Url::toRoute(['photobooks/page-svg-thumb', 'id'=>$id, 'ref'=>$ref]); ?>" src="<?php echo Url::toRoute(['photobooks/page-svg-thumb', 'id'=>$id, 'ref'=>$ref, 'page'=>$key]); ?>" />
                        </a>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <span class="label"><?php echo Yii::t('app', 'Разворот ') ?><?php echo $key; ?></span>
                        </div>
                        <div class="col-md-6">
                            <select disabled class="form-control action-select-<?php echo $key; ?>" data-index="<?php echo $key; ?>">
                                <option value="print" style="color: #00ee00;" <?php  if(!empty($page['action']) && $page['action']=='print') echo 'selected'; ?>>Печатать</option>
                                <option value="processing" style="color: #999;" <?php  if(!empty($page['action']) && $page['action']=='processing') echo 'selected'; ?>>На доработке</option>
                                <option value="delete" style="color: #ff0000;">Удалить</option>
                            </select>
                        </div>
                    </div>

                </div>

            <?php endforeach; ?>
        </div>

        <label><?php echo Yii::t('app', 'Итого разворотов к печати: {count}', ['count'=>$print_count]); ?></label>


        <br/>  <br/>

        <div class="pull-right">

            <?php if($back=='edit'): ?>



                <a href="<?php echo Url::toRoute(['photobooks/edit',  'ref'=>$ref, 'id'=>$id])  ?>"  class="turn-on-editable btn btn-gray btn  button-2-line btnCancel"
                   data-url="<?php echo Url::toRoute(['photobooks/edit', 'ref'=>$ref, 'id'=>$id]); ?>" style="min-height: 55px" >



                            <span class="button-col" style="padding-top:7px;">
                            <?php echo Yii::t('app', 'Отмена'); ?>
                            </span>
                </a>
            <?php else: ?>

                <a href="<?php echo Url::toRoute(['photobooks/index']);  ?>"  class="turn-on-editable btn btn-gray btn  button-2-line btnCancel"
                   data-url="<?php echo Url::toRoute(['photobooks/index']); ?>" style="min-height: 55px" >



                            <span class="button-col" style="padding-top:7px;">
                            <?php echo Yii::t('app', 'Отмена'); ?>
                            </span>
                </a>
            <?php endif; ?>

            <a class="turn-on-editable btn btn-primary button-2-line" href="<?php echo Url::toRoute(['photobooks/checkout', 'ref'=>$ref, 'id'=>$id]); ?>"  >

                            <span class="button-col button-icon">
                                <i class="fa fa-shopping-cart"></i>
                            </span>
                            <span class="button-col" style="padding-top:7px;">
                            <?php echo Yii::t('app', 'Продолжить'); ?>
                            </span>
            </a><br/><br/><br/>
        </div>


    </div>
</div>








<div class="loader">

    <div class="place">
        <i class="anim glyphicons rotation_lock"></i><br/>

        <label><?php echo Yii::t('app', 'Поворачиваем...') ?></label>

    </div>
</div>








