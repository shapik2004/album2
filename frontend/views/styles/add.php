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

<!--
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div>
        </div>

    </div>
</div>-->
