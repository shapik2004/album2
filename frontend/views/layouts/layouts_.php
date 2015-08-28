<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\PhotobookAsset;
use frontend\widgets\Alert;
use yii\helpers\Url;
use app\components\UserUrl;
use yii\web\View;


/* @var $this \yii\web\View */
/* @var $content string */

PhotobookAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>


    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.5/angular.min.js"></script>
    <script src="/js/fabricjs.js"></script>


    <link href="<?php echo UserUrl::cssUrl($this->params['css_file_id']); ?>" rel="stylesheet">

    <script src="/frontend/web/glyphicons/scripts/modernizr.js"></script>

</head>
<body>
    <?php $this->beginBody() ?>

    <div id="wrapper" class="wrapper">

        <nav id="navbar" class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?php echo Url::home(); ?>"><img src="<?php echo UserUrl::logoUrl($this->params['logo_url'], UserUrl::IMAGE_SMALL,'jpg', $this->params['ref_user_id']) ?>" height="49" /></a>
                </div>


                <div class="navbar-form navbar-right" >

                    <div class="btn-group">

                        <a class="btn btn-primary button-2-line ">
                            <div class="button-col button-icon ">
                                <i class="fa fa-archive"></i>
                            </div>
                            <div class="button-col">
                                <?php echo Yii::t('app', 'Скачать ZIP <br/>архив с фото'); ?>
                            </div>
                            <!-- <div class="button-col button-caret">
                                 <i class="fa fa-chevron-down"></i>
                             </div>-->
                        </a>



                    </div>

                    <div class="btn-group">

                        <a class="btn btn-primary button-2-line " >
                            <div class="button-col button-icon ">
                                <i class="fa fa-image"></i>
                            </div>
                            <div class="button-col">
                                <?php echo Yii::t('app', 'Загрузить <br/>обработанные фото'); ?>
                            </div>
                            <!-- <div class="button-col button-caret">
                                 <i class="fa fa-chevron-down"></i>
                             </div>-->
                        </a>



                    </div>



                    <div class="btn-group">

                        <a class="btn btn-primary button-2-line " href="<?php echo Yii::$app->urlManager->createUrl(['photobooks/edit', 'id'=> $this->params['id'], 'ref'=>$this->params['ref'] ]); ?>">
                            <div class="button-col button-icon ">
                                <i class="glyphicons book_open"></i>
                            </div>
                            <div class="button-col">
                                <?php echo Yii::t('app', 'Редактировать<br/> фотокнигу'); ?>
                            </div>
                        </a>



                    </div>




                </div>



            </div>
        </nav>






            <?= Alert::widget() ?>
            <?= $content ?>


        <div class="push"></div>
    </div>


    <!--<div id="footer" class="footer ">
        <div class="panel-footer ">

        </div>
    </div>-->


    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
