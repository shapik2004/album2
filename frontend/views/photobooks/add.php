<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use app\components\UserUrl;



$this->title = Yii::t('app','Заказы');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="photobook-index">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">

                <div class="row">
                    <div class="col-xs-12">
                        <h2 ><?= Html::encode($this->title); ?></h2>
                    </div>
                </div>
                <hr />

                <div class="row" >
                    <div class="col-xs-12" >

                         <div class="row" >
                            <div class="col-xs-7" >
                                <h3><?php echo  Yii::t('app', 'Заказы в работе'); ?></h3>
                            </div>

                            <div  class="col-xs-5" style="padding-top: 5px;;">
                                <a href="#" class="btn btn-primary pull-right"><?php echo Yii::t('app', 'Добавить заказ'); ?></a>
                            </div>
                         </div>

                        <hr />
                        <div class="row" >
                            <div class="col-xs-12" >
                                <p><?php echo Yii::t('app', 'Не найдено заказов работе') ?></p>
                            </div>


                        </div>

                    </div>
                </div>





                <div class="row" >
                    <div class="col-xs-12" >
                        <h3><?php echo  Yii::t('app', 'Заказы отправленные в печать'); ?></h3>
                        <hr />
                        <p><?php echo Yii::t('app', 'Не найдено заказов отправленых в печать') ?></p>
                    </div>
                </div>


                <div class="row" >
                    <div class="col-xs-12" >
                        <h3><?php echo  Yii::t('app', 'Архив заказов'); ?></h3>
                        <hr />
                        <p><?php echo Yii::t('app', 'Не найдено архивных заказов') ?></p>
                    </div>
                </div>



            </div>
        </div>
    </div>





</div>
