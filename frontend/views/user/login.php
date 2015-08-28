<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('app', 'Авторизация');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h2><?= Html::encode($this->title) ?></h2>

    <p><?php echo Yii::t('app', 'Пожалуйста, заполните следующие поля для входа'); ?></p>

    <div class="row">
        <div class="col-lg-12">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->begin(); ?>
                    <?=  Html::activeLabel($model,'username', ['label' => Yii::t('app', 'Email')]); ?>
                    <?=  Html::activeTextInput($model, 'username', ['class' => 'form-control']); ?>
                    <?=  Html::error($model,'username', ['class' => 'help-block help-block-error']); ?>
                <?= $form->field($model, 'username')->end(); ?>

                <?= $form->field($model, 'password')->begin(); ?>
                    <?=  Html::activeLabel($model,'password', ['label' => Yii::t('app', 'Пароль')]); ?>
                    <?=  Html::activePasswordInput($model, 'password', ['class' => 'form-control']); ?>
                    <?=  Html::error($model,'password', ['class' => 'help-block help-block-error']); ?>
                <?= $form->field($model, 'password')->end(); ?>


                <?= $form->field($model, 'rememberMe')->begin(); ?>
                    <div class="checkbox">
                        <?=  Html::activeCheckbox($model, 'rememberMe', ['label'=>Yii::t('app', 'Запомнить меня?')]); ?>
                    </div>
                <?= $form->field($model, 'rememberMe')->end(); ?>



                <div style="color:#999;margin:1em 0">
                    <?= Html::a(Yii::t('app', 'Забыли пароль?'), ['user/request-password-reset']) ?>.
                </div>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app','Войти'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
