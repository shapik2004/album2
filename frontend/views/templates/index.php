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

$this->title = 'Макеты';

$this->title = Yii::t('app','Макеты');
$this->params['breadcrumbs'][] = $this->title;

/*echo $work_orders[0]->name;
print_r($work_orders);*/


use frontend\assets\TemplatesIndexAsset;
PhotobooksIndexAsset::register($this);


?>

<div class="templates-index">
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
                    <li role="presentation" class="<?php if($attr['active']) { echo "active";  $active=$attr['params'];}?>"><a href="<?php echo Yii::$app->urlManager->createUrl(['templates/index']+$attr['params']); ?>" ><?php echo $attr['label'] ?></a></li>
                    <?php endforeach; ?>
                    <li role="presentation" class="pull-right tabs-button" > <a href="<?php echo Yii::$app->urlManager->createUrl(['templates/add']); ?>" target="_blank" ><button  class="btn btn-primary"><?php echo Yii::t('app', 'Добавить макет'); ?></button></a>

                </ul>



                <ul class="  nav-pills pull-right " >
                    <?php foreach ($filters_ph as $i=>$ph_value): ?>
                        <a type="button" href="<?php echo Yii::$app->urlManager->createUrl(['templates/index', 'ph'=>$ph_value['ph']]+$active); ?>" class="btn btn-link <?php if($ph_value['ph']==$ph) echo 'active'; ?>"><?php echo $ph_value['label'] ?></a>
                    <?php endforeach; ?>

                    </ul>


                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="home">


                        <br/><br/><br/>
                        <?php if(count($templates)>0): ?>
                        <div class="row">

                            <?php foreach($templates as $key=>$template): ?>

                                <div class="col-xs-6 col-md-3">

                                    <div class="thumbnail">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(['templates/edit', 'id'=> $template->id]); ?>"  class="thumbnail" style="margin-bottom: 5px;">
                                            <img  src="<?php echo UserUrl::templateThumb(true, $template->id).'.jpg?r='.rand(0,9999); ?>" />

                                        </a>
                                        <span>
                                            &nbsp;<?php echo $template->name; ?>
                                        </span>
                                        <span class="pull-right">

                                            <i class="fa fa-photo"></i> х <?php echo $template->count_placeholder ?>

                                            <?php if($template->text_object) echo '<i class="fa fa-font"></i>'?>

                                            <span class="green"><?php if($template->publish) echo '<i class="green fa fa-check"></i>'?></span>
                                        </span>
                                    </div>


                                </div>

                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>

                        <?php echo Yii::t('app', 'Макеты не найдены'); ?>

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
