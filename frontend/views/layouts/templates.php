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
use common\models\User;


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




    <!--<script src="/js/fabricjs.js"></script>-->





    <link href="<?php echo UserUrl::cssUrl($this->params['css_file_id']); ?>" rel="stylesheet">

    <script src="/frontend/web/glyphicons/scripts/modernizr.js"></script>

</head>
<body data-demo="<?php if($this->params['demo']) echo '1'; else echo '0'; ?>">
    <?php $this->beginBody() ?>

    <div id="wrapper" class="wrapper wrapper-template-edit" >

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
                    <a class="navbar-brand" href="<?php echo Url::home(); ?>"><img src="<?php echo UserUrl::logoUrl($this->params['logo_url'], UserUrl::IMAGE_SMALL,'png', $this->params['ref_user_id']) ?>" height="49" /></a>
                </div>


                <div class="navbar-form navbar-right" >

                    <div class="btn-group">


                            <button type="button" class="btn btn-primary button-2-line dropdown-toggle" data-toggle="dropdown">
                                <div class="button-col button-icon ">
                                    <i class="glyphicons user"></i>
                                </div>
                                <div class="button-col">
                                    <?php echo Yii::$app->user->identity->username; ?><br/>настройки
                                </div>
                                <div class="button-col button-caret">
                                    <i class="fa fa-chevron-down"></i>
                                </div>
                            </button>


                            <ul class="dropdown-menu" role="menu">
                                <?php if(Yii::$app->user->identity->role==User::ROLE_ADMIN): ?>
                                    <li><a data-toggle="modal" href="<?php echo Url::toRoute('super-admin/settings'); ?>"><?php echo Yii::t('app', 'Настройки системы') ?></a></li>
                                    <li><a data-toggle="modal" href="<?php echo Url::toRoute('styles/index'); ?>"><?php echo Yii::t('app', 'Стили') ?></a></li>
                                    <li><a data-toggle="modal" href="<?php echo Url::toRoute('covers/index'); ?>"><?php echo Yii::t('app', 'Обложки') ?></a></li>
                                    <li><a data-toggle="modal" href="<?php echo Url::toRoute('templates/index'); ?>"><?php echo Yii::t('app', 'Макеты') ?></a></li>
                                    <li><hr/></li>
                                <?php endif; ?>

                                <li><a data-toggle="modal" href="<?php echo Url::toRoute('user/profile'); ?>"><?php echo Yii::t('app', 'Аккаунт') ?></a></li>
                                <li><a data-toggle="modal" href="<?php echo Url::toRoute('user/settings'); ?>"><?php echo Yii::t('app', 'Настройки пользователя') ?></a></li>
                                <li><a data-toggle="modal" href="<?php echo Url::toRoute('photobooks/index'); ?>"><?php echo Yii::t('app', 'Мои проекты') ?></a></li>
                                <li><a data-toggle="modal" href="<?php echo Url::toRoute('user/logout'); ?>"><?php echo Yii::t('app', 'Выход'); ?></a></li>

                            </ul>





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
