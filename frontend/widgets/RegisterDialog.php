<?php

namespace frontend\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;

use yii\base\Widget;
use app\components\UserUrl;



class RegisterDialog extends Widget
{

    public $only_body=false;

    public $model;



    public function init()
    {
        parent::init();


    }

    public function run()
    {
?>


        <?php if(!$this->only_body): ?>

        <!-- Modal -->
        <div class="modal fade" id="registerDialog" tabindex="-1" role="dialog" aria-labelledby="registerDialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="registerDialog">Регистрация</h4>
                    </div>
        <?php endif; ?>
                    <div class="modal-replace">
                        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                        <div class="modal-body">



                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-xs-12">


                                        <?= $form->field($this->model, 'username') ?>
                                        <?= $form->field($this->model, 'email') ?>
                                        <?= $form->field($this->model, 'password')->passwordInput() ?>




                                    </div>
                                </div>
                            </div>





                        </div>
                        <div class="modal-footer">

                            <button type="button" class="btn btn-primary btnDialogAddPhotobookClose pull-left">Закрыть</button>

                            <div class="form-group">
                                <?= Html::submitButton('Signup', ['class' => 'btn btn-primary pull-right', 'name' => 'signup-button']) ?>
                            </div>
                        </div>

                        <?php ActiveForm::end(); ?>

                    </div>

                    <?php ?>
        <?php if(!$this->only_body): ?>
                </div>
            </div>
        </div>

        <?php endif; ?>

<?php
        //$this->render();
    }


}
