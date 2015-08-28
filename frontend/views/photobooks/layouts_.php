<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

use yii\helpers\Url;



use frontend\assets\LayoutsAsset;
LayoutsAsset::register($this);

$this->title = Yii::t('app','Выбор стиля');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="photobook-upload-photos ">

    <ul class="breadcrumb"><li><div class="editable" data-url="<?php echo  Url::toRoute(['photobook-api/change-name', 'ref'=>$ref, 'id'=>$id, 'name'=>'newname']); ?>"><?php echo $model->name; ?></div></li>
    </ul>


    <div class="container-fluid scroll" style="margin-bottom: 125px;">
        <div class="row">
            <div class="col-xs-12">

                <div class="row">
                    <div class="col-xs-12">
                        <h2 ><?= Html::encode($this->title); ?></h2>
                    </div>
                </div>
                <hr />



                <div class="row">
                <?php foreach($pages as $key=>$page): ?>

                    <div class="col-xs-4 col-md-2">
                        <a href="#" class="thumbnail">

                            <svg width="100%" viewBox="0 0 700 350">
                                <?php echo $page['svg']; ?>
                            </svg>

                        </a>

                    </div>

                <?php endforeach; ?>

                </div>





            </div>
        </div>

    </div>

    <div class="container-fluid " style="position: fixed; left: 0px; right: 0px; bottom: 0px; height: 125px;" >
        <div class="row">
            <div class="col-xs-12">
                <?php foreach($styles as $style_key=>$style): ?>

                    <div class="col-xs-4 col-md-2  ">
                        <a href="<?php echo Yii::$app->urlManager->createUrl(['photobooks/layouts', 'ref'=>$ref,  'id'=> $id, 'style_id'=>$style->id]); ?>" class="thumbnail <?php if($style->id==$model->style_id) echo 'active';?>">
                            <img  data-src="holder.js/100%x75" />
                            <center><?php  echo $style->name; ?></center>
                        </a>
                    </div>


                <?php endforeach; ?>
            </div>





        </div>

    </div>





</div>

