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
use common\components\Utils;

//$this->title = 'Фотокнига';

$this->title = Yii::t('app','Пользователи');
$this->params['breadcrumbs'][] = $this->title;

/*echo $work_orders[0]->name;
print_r($work_orders);*/


use frontend\assets\SuperAdminUsersAsset;
SuperAdminUsersAsset::register($this);


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
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">

                <div class="row">
                    <div class="col-xs-12">
                      <!--  <button  class="btn btn-primary pull-right btnAddPhotobook"><?php echo Yii::t('app', 'Добавить заказ'); ?></button>-->
                    </div>
                </div>

            </div>
        </div>

        <div  class="row">
            <div class="col-xs-3">


                <div class="list-group">
                <?php if(!empty($sidemenus)): ?>
                    <?php foreach($sidemenus as $key=>$menuitem): ?>

                        <a href="<?php echo Yii::$app->urlManager->createUrl(['super-admin/users', 'role'=>$menuitem['role']]); ?>" class="list-group-item <?php if($role==$menuitem['role']) echo 'active'; ?>">
                            <?php if($menuitem['count']>0): ?><span class="badge"><?php echo  $menuitem['count'] ; ?></span><?php endif; ?>
                            <?php echo $menuitem['title'] ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
               </div>
            </div>
            <div class="col-xs-9">



                <!-- Tab panes -->

                <div class="well">

                    <form class="form-inline">


                        <input type="hidden" class="form-control" name="role" value="<?php echo $role; ?>">
                        <div class="form-group">
                            <!--<label for="exampleInputName2">ID</label>-->
                            <input type="text" class="form-control" name="user_id" value="<?php echo $filter['user_id']; ?>" placeholder="Введите id">
                        </div>
                        <div class="form-group">
                            <!--<label for="exampleInputEmail2">Email</label>-->
                            <input type="email" class="form-control" name="email" value="<?php echo $filter['email']; ?>" placeholder="Введите email">
                        </div>

                        <div class="form-group">
                            <!--<label for="exampleInputEmail2">Имя</label>-->
                            <input type="email" class="form-control" name="username" value="<?php echo $filter['username']; ?>" placeholder="Введите имя">
                        </div>
                        <a class="btn btn-gray" href="<?php echo Url::toRoute(['super-admin/users', 'role'=>$role]) ?>"> <i class="fa fa-remove"></i> </a>
                        <button type="submit" class="btn btn-primary pull-right"> <i class="fa fa-search"></i> Поиск</button>


                    </form>

                </div>


                        <?php if(count($users)>0): ?>
                        <?php foreach($users as $key=>$user): ?>

                                <div class="user-item">
                                <div class="user-order">


                                        <div class="row">

                                            <div class="col-xs-6">

                                            </div>
                                            <div class="col-xs-6">
                                                <!--<div class="photobook-index-date-time pull-right">
                                                    <?php echo Yii::t('app', 'Аккаунт создан: {created}', ['created'=>date("d-m-Y h:i:s",  $user->created_at)]) ?>
                                                    <?php echo Yii::t('app', 'Аккаунт изменен: {created}', ['created'=>date("d-m-Y h:i:s",  $user->updated_at)]) ?>
                                                </div>-->
                                            </div>
                                        </div>



                                        <div class="row">
                                            <div class="col-xs-2">
                                                <a href="#" class="thumbnail" style="width: 100%; cursor: pointer;">

                                                    <?php if(!empty($users_settings[$user->id]) && $users_settings[$user->id]->logo_url!='default-logo'): ?>


                                                        <img src="<?php echo UserUrl::logoUrl($users_settings[$user->id]->logo_url, UserUrl::IMAGE_SMALL, 'png', $user->id) ?>" />

                                                    <?php else: ?>
            
                                                        <img src="/images/style_default.jpg" />

                                                    <?php endif; ?>

                                                </a>
                                            </div>
                                            <div class="col-xs-4">


                                                <span class="project-title"><?php echo trim($user->username); ?> <br/><?php echo $user->email; ?> <br/> ID: <?php echo $user->id; ?></span>

                                            </div>

                                            <div class="col-xs-4">

                                                <div class="user-index-tools">


                                                    <a class="pull-right tooltips"  href="<?php echo Url::toRoute(['super-admin/user-edit', 'id'=>$user->id]); ?>" >
                                                        <?php echo Yii::t('app', 'Редактировать'); ?>
                                                    </a><br/>


                                                </div>

                                            </div>
                                        </div>


                                </div>
                                <div class="highlight">
                                    <!--<div class="photobook-order-footer"><?php echo Yii::t('app', 'Статус соглосования'); ?></div>-->
                                </div>
                                </div>



                        <?php endforeach; ?>
                        <?php else: ?>

                        <?php echo Yii::t('app', 'Пользователей не найдено'); ?>

                        <?php endif; ?>



                <?php echo LinkPager::widget([
                    'pagination' => $pages,
                ]); ?>





            </div>
        </div>
    </div>





</div>


<div class="loader">

    <div class="place">
        <i class="anim glyphicons rotation_lock"></i><br/>
        <label><?php echo Yii::t('app', 'Поворачиваем...') ?></label>
    </div>
</div>



