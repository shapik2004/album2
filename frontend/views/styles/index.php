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
use  frontend\assets\PhotobooksIndexAsset;

$this->title = 'Стили';

$this->title = Yii::t('app','Стили');
$this->params['breadcrumbs'][] = $this->title;

/*echo $work_orders[0]->name;
print_r($work_orders);*/


use frontend\assets\TemplatesIndexAsset;
PhotobooksIndexAsset::register($this);


?>

<div class="styles-index">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">

                <div class="row">
                    <div class="col-xs-12">
                        <h2 ><?= Html::encode($this->title); ?></h2>
                    </div>
                </div>
                <hr />

                <ul class="nav nav-tabs" role="tablist">
                    <?php $active=[]; foreach ($filters_attr as $key=>$attr): ?>
                    <li role="presentation" class="<?php if($attr['active']) { echo "active";  $active=$attr['params'];}?>"><a href="<?php echo Yii::$app->urlManager->createUrl(['styles/index']+$attr['params']); ?>" ><?php echo $attr['label'] ?></a></li>
                    <?php endforeach; ?>
                    <li role="presentation" class="pull-right tabs-button" > <a href="<?php echo Yii::$app->urlManager->createUrl(['styles/add']); ?>" target="_blank" ><button  class="btn btn-primary"><?php echo Yii::t('app', 'Добавить стиль'); ?></button></a>
                </ul>




                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="home">


                        <br/><br/><br/>
                        <?php if(count($styles)>0): ?>
                        <div class="row">

                            <?php foreach($styles as $key=>$style): ?>

                                <div class="col-xs-3 col-md-3">

                                    <div class="thumbnail">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(['styles/edit', 'id'=> $style->id]); ?>" class="thumbnail" style="margin-bottom: 5px;">
                                            <?php if($style->thumb_key=='style_default' || $style->thumb_key=='default_style_thumb' || empty($style->thumb_key)): ?>
                                                <img class="style-min-thumb" src="/images/style_default.jpg"/>
                                            <?php else: ?>
                                                <img class="style-min-thumb" src="<?php echo UserUrl::styleThumb(true, $style->id).'/'.UserUrl::imageFile($style->thumb_key, UserUrl::IMAGE_THUMB) ?>"/>
                                            <?php endif; ?>

                                        </a>

                                        <span><?php  echo $style->name; ?></span>
                                        <span class="pull-right">
                                            <i class="fa fa-th-large"></i> x <?php  echo $style->max_spread; ?>
                                            <span class="green">
                                                <?php if($style->status==1) echo '<i class="green fa fa-check"></i>'?>
                                            </span>
                                        </span>


                                    </div>

                                </div>

                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>

                        <?php echo Yii::t('app', 'Стили не найдены'); ?>

                        <?php endif; ?>

                    </div>

                </div>


                <?php echo LinkPager::widget([
                    'pagination' => $pages,
                ]); ?>








            </div>
        </div>
    </div>





</div>
