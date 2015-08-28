<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?php echo Yii::t('app', 'Пожалуйста, заполните следующие поля для того, чтоб мы могли сохранить ваши проекты'); ?></p>

    <div class="row">
        <div class="col-xs-12">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'username')->begin(); ?>
                <?=  Html::activeLabel($model,'username', ['label' => Yii::t('app', 'Ваше имя')]); ?>
                <?=  Html::activeTextInput($model, 'username', ['class' => 'form-control']); ?>
                <?=  Html::error($model,'username', ['class' => 'help-block help-block-error']); ?>
                <?= $form->field($model, 'username')->end(); ?>

                <?= $form->field($model, 'email')->begin(); ?>
                <?=  Html::activeLabel($model,'email', ['label' => Yii::t('app', 'Email')]); ?>
                <?=  Html::activeTextInput($model, 'email', ['class' => 'form-control']); ?>
                <?=  Html::error($model,'email', ['class' => 'help-block help-block-error']); ?>
                <?= $form->field($model, 'email')->end(); ?>

                <?= $form->field($model, 'password')->begin(); ?>
                <?=  Html::activeLabel($model,'password', ['label' => Yii::t('app', 'Пароль')]); ?>
                <?=  Html::activePasswordInput($model, 'password', ['class' => 'form-control']); ?>
                <?=  Html::error($model,'password', ['class' => 'help-block help-block-error']); ?>
                <?= $form->field($model, 'password')->end(); ?>





                <div class="form-group">
                    <?= Html::submitButton('Все верно', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
