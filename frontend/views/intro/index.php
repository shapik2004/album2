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
use common\models\User;

$this->title = 'Sensation Album';

$this->title = Yii::t('app','Home');
$this->params['breadcrumbs'][] = $this->title;

/*echo $work_orders[0]->name;
print_r($work_orders);*/


use frontend\assets\IntroAsset;
IntroAsset::register($this);


?>

<nav id="navbar" class="navbar navbar-intro" role="navigation">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->

        <?php  if(Yii::$app->user->identity): ?>
        <div class="navbar-form navbar-right" >


            <?php if($this->params['demo']==1): ?>

                <div class="btn-group">

                    <a type="button" class="btn btn-default button-1-line " href="<?php echo Url::toRoute('user/logout'); ?>" >
                        <div class="button-col button-icon ">
                            <i class="glyphicons lock"></i>
                        </div>
                        <div class="button-col">
                            <?php echo Yii::t('app', 'Войти'); ?>
                        </div>

                    </a>

                </div>

            <?php endif; ?>

            <div class="btn-group">

                <button type="button" class="btn btn-primary button-1-line dropdown-toggle" data-toggle="dropdown">
                    <div class="button-col button-icon ">
                        <i class="glyphicons user"></i>
                    </div>
                    <div class="button-col">
                        <?php echo Yii::$app->user->identity->username; ?>
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
                    <li><a data-toggle="modal" href="<?php echo Url::toRoute('super-admin/users'); ?>"><?php echo Yii::t('app', 'Пользователи') ?></a></li>
                    <li><a data-toggle="modal" href="<?php echo Url::toRoute('super-admin/photobooks'); ?>"><?php echo Yii::t('app', 'Заказы') ?></a></li>
                    <li><hr/></li>
                    <?php endif; ?>
                    <li><a data-toggle="modal" href="<?php echo Url::toRoute('photobooks/index'); ?>"><?php echo Yii::t('app', 'Мои проекты') ?></a></li>
                    <li><a data-toggle="modal" href="<?php echo Url::toRoute('user/profile'); ?>"><?php echo Yii::t('app', 'Аккаунт') ?></a></li>
                    <li><a data-toggle="modal" href="<?php echo Url::toRoute('user/settings'); ?>"><?php echo Yii::t('app', 'Настройки пользователя') ?></a></li>
                    <li><a data-toggle="modal" href="<?php echo Url::toRoute('user/logout'); ?>"><?php echo Yii::t('app', 'Выход'); ?></a></li>

                </ul>
            </div>

        </div>
        <?php endif; ?>



    </div>
</nav>



<div class="row intro-bg">
            <div class="col-xs-12">


                    <div class="row">
                        <div class="col-xs-5">
                        </div>
                        <div class="col-xs-7">
                            <div class="sensation-logo">
                                <img src="/frontend/web/images/sensation_logo.png" />
                            </div>
                            <div class="sensation-slogan">
                                Классические альбомы паспарту
                            </div>
                            <div class="sensation-buttons">
                                <a href="<?php echo Yii::$app->urlManager->createUrl(['photobooks/demo', 'ref'=>AlphaId::id(Yii::$app->user->identity->getId())]); ?>" class="btn btn-intro">Пробывать демо</a>
                                <a  href="<?php if($this->params['demo']==1) echo  Url::toRoute('user/signup-demo'); else echo '#'; ?>" class="btn btn-intro <?php if($this->params['demo']!=1) echo 'btnAddPhotobook'; ?>">Создать альбом</a>
                                <a   href="<?php echo Yii::$app->urlManager->createUrl(['photobooks/index', 'ref'=>AlphaId::id(Yii::$app->user->identity->getId())]); ?>" class="btn btn-intro">Мои проекты</a>
                            </div>
                        </div>
                    </div>


            </div>
        </div>






</div>




<!-- Modal -->
<div class="modal fade" id="dialogAddPhotobook" tabindex="-1" role="dialog" aria-labelledby="dialogAddPhotobook" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="dialogAddPhotobook">Создание новой фотокниги</h4>
            </div>
            <div class="modal-body">



                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-12">

                            <label><?php echo Yii::t('app', 'Имя 1'); ?></label>
                            <input id="inputName1" class="form-control text" placeholder="<?php echo Yii::t('app', 'Введите имя'); ?>" style=" padding-top: 6px; padding-bottom: 6px;" />

                            <label><?php echo Yii::t('app', 'Символ &'); ?></label>
                            <input id="inputAnd" class="form-control text" placeholder="<?php echo Yii::t('app', "Введите '&'"); ?>" style=" padding-top: 6px; padding-bottom: 6px;" />

                            <label><?php echo Yii::t('app', 'Имя 2'); ?></label>
                            <input id="inputName2" class="form-control text" placeholder="<?php echo Yii::t('app', "Введите имя 2"); ?>" style=" padding-top: 6px; padding-bottom: 6px;" />

                            <label><?php echo Yii::t('app', 'Копирайт'); ?></label>
                            <input id="inputCopyright" class="form-control text" placeholder="<?php echo Yii::t('app', "Введите Ваш копирайт"); ?>" style=" padding-top: 6px; padding-bottom: 6px;" />


                        </div>
                    </div>
                </div>




            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary btnDialogAddPhotobookClose pull-left">Закрыть</button>

                <button type="button" class="btn btn-primary btnDialogCreatePhotobook pull-right" data-url="<?php echo Url::toRoute(['photobook-api/create-photobook']); ?>">Создать книгу</button>
            </div>
        </div>
    </div>
</div>

