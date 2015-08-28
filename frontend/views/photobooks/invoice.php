<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

use yii\helpers\Url;

use app\components\UserUrl;

use  common\components\Utils;

use app\components\CurrencyConvertor;

use frontend\assets\InvoiceAsset;

use common\models\Invoice;

use common\models\Photobook;





InvoiceAsset::register($this);

$this->title = Yii::t('app','Оформление заказа');
$this->params['breadcrumbs'][] = $this->title;
?>


<nav id="navbar" class="navbar navbar-default" role="navigation">

    <div class="container-fluid ">




        <div class="row">
            <div class="col-lg-5  col-md-3 ">
                <div class="navbar-header">
                    <a class="navbar-brand" ><?php echo Yii::t('app', 'Счет-фактура') ?> <b><?php  echo Yii::t('app', '№ {num}', ['num'=>$invoice->id] )?></b></a>
                </div>
            </div>
            <div class="col-lg-7 col-md-9  header-buttons">


                <div class="navbar-form navbar-right" >


                    <?php if($invoice->status==Invoice::STATUS_NEW): ?>
                    <div class="btn-group"  >
                    <div><?php echo Yii::t('app', 'Способ оплаты:'); ?></div>
                        <select class="form-control col-xs-3 paymentTypeSelect">
                            <?php foreach($payment_types as $key=>$payment_type): ?>
                                <option <?php if($key==$invoice->payment_type) echo 'selected' ?> class="type-<?php echo $key; ?>" value="<?php echo $key; ?>" data-online="<?php echo $payment_type['online']; ?>"><?php echo $payment_type['title']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="btn-group onlinePayButton" <?php if(!$payment_types[$invoice->payment_type]['online'])  echo 'style="display:none;"'?>>
                        <a class="btn btn-primary button-2-line btnPay" data-url="<?php  echo Url::toRoute(['photobooks/invoice-pay', 'id'=>$invoice->id]); ?>" data-type="<?php echo $invoice->payment_type; ?>"  >
                            <div class="button-col button-icon ">
                                <i class="fa fa-cc-amex"></i>
                            </div>
                            <div class="button-col">
                                <?php echo Yii::t('app', 'Оплатить<br/>счет'); ?>
                            </div>
                        </a>

                    </div>

                    <div class="btn-group offlineInfo" <?php if($payment_types[$invoice->payment_type]['online'])  echo 'style="display:none;"'?>>

                        <a class="btn btn-gray button-2-line"  >
                            <div class="button-col button-icon ">
                                <i class="fa fa-phone"></i>
                            </div>
                            <div class="button-col">
                                <?php echo Yii::t('app', 'Для оплаты свяжитесь <br/>с нами по телефону: 0 77 777 77 77') ?>
                            </div>
                        </a>



                    </div>





                    <?php elseif($invoice->status==Invoice::STATUS_PAID): ?>

                        <div class="btn-group">

                            <a class="btn btn-primary button-2-line"  disabled >
                                <div class="button-col button-icon ">
                                    <i class="fa fa-check"></i>
                                </div>
                                <div class="button-col" style="padding-top: 7px;">
                                    <b><?php echo Yii::t('app', 'Оплачено') ?></b>
                                </div>
                            </a>



                        </div>


                    <?php elseif($invoice->status==Invoice::STATUS_CANCEL): ?>

                        <div class="btn-group">

                            <a class="btn btn-gray button-2-line"  disabled >
                                <div class="button-col button-icon ">
                                    <i class="fa fa-remove"></i>
                                </div>
                                <div class="button-col" style="padding-top: 7px;">
                                    <b><?php echo Yii::t('app', 'Отменено') ?></b>
                                </div>
                            </a>



                        </div>


                    <?php else: ?>

                        <div class="btn-group">

                            <a class="btn btn-gray button-2-line"  disabled >
                                <div class="button-col button-icon ">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                                <div class="button-col" style="padding-top: 7px;">
                                    <b><?php echo Yii::t('app', 'Просрочено') ?></b>
                                </div>
                            </a>



                        </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</nav>





<div class="row">
    <div class="col-xs-12">

        <div class="upload-errors">

        </div>
    </div>
</div>


<div class="row">
    <div class="col-xs-12">



        <!--<div class="alert alert-info"  role="alert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <p><span class="brand-color">Примечание. </span>Это техническая страница созданая для примера.</p>
            <p class="gray">Тут можно скачть макеты, в реальной ситуации макеты доступны только в админке!
            </p>
        </div>-->



        <div class="table-responsive">
            <table  class="table"  >
                <thead>

                    <th>№</th>
                    <th><?php echo Yii::t('app', 'Наименование') ?></th>
                    <th  align="right" style="text-align: right;"><?php echo Yii::t('app', 'Цена') ?></th>
                    <th style="text-align: center;"><?php echo Yii::t('app', 'Количество экземпляров') ?></th>
                    <th  align="right" style="text-align: right;"><?php echo Yii::t('app', 'Сумма') ?></th>

                </thead>

                <tbody>

                    <?php foreach($invoice->data['rows'] as $key=>$row): ?>
                    <tr>
                        <td class="row-index" width="70">
                            <?php echo $key+1; ?>
                        </td>
                        <td width="30%">
                            <?php echo $row['title']; ?>
                        </td>
                        <td align="right" width="150">
                            <?php  printf('%.2f', $row['price']); ?> <?php echo $invoice->currency; ?>
                        </td>
                        <td width="100" style="text-align: center;">
                            <?php echo $row['quantity']; ?>

                        </td>
                        <td width="150" align="right">

                            <?php  printf('%.2f', $row['sub_total']); ?> <?php echo $invoice->currency; ?>
                        </td>

                    </tr>
                    <?php endforeach ?>

                    <tr>
                        <td>
                        </td>
                        <td>


                        </td>
                        <td>

                        </td>
                        <td style="text-align: center;">
                           Итого:
                        </td>
                        <td align="right" id="totalDisplay">
                            <?php  printf('%.2f', $invoice->total); ?> <?php echo $invoice->currency; ?>
                        </td>

                    </tr>
                </tbody>

            </table>
        </div>


        <h3><?php echo Yii::t('app', 'Адрес доставки и телефон контактного лица:') ?></h3>
        <?php echo Yii::$app->user->identity->delivery_address; ?>





        <br/>  <br/>

        <div class="pull-left">

           <!-- <a href="<?php echo Url::toRoute(['photobooks/index'])  ?>"  class="turn-on-editable btn btn-gray btn  button-2-line btnCancel"
               data-url="<?php echo Url::toRoute(['photobooks/index']); ?>" style="min-height: 55px" >



                            <span class="button-col" style="padding-top:7px;">
                            <?php echo Yii::t('app', 'Вернуться и создать еще одну фотокнигу'); ?>
                            </span>
            </a>-->

            <a class="turn-on-editable btn btn-primary button-2-line" href="<?php echo Url::toRoute(['photobooks/index', 'status'=>Photobook::STATUS_READY_FOR_PRINT_WAIT_PAYMENT]); ?>"  >

                            <span class="button-col button-icon">
                                <i class="fa fa-arrow-left"></i>
                            </span>
                            <span class="button-col" style="padding-top:7px;">
                            <?php echo Yii::t('app', 'Готово, вернуться на сайта'); ?>
                            </span>
            </a><br/><br/><br/>
        </div>


        <?php if($invoice->status==Invoice::STATUS_NEW): ?>
        <div class="pull-right">
            <a class="turn-on-editable btn btn-gray button-2-line" href="<?php echo Url::toRoute(['photobooks/cancel-invoice', 'id'=>$invoice->id]); ?>"  >

                            <span class="button-col button-icon">
                                <i class="fa fa-remove"></i>
                            </span>
                            <span class="button-col" style="padding-top:7px;">
                          <?php echo Yii::t('app', 'Аннулировать счет'); ?>
                            </span>
            </a>
        </div>
        <?php endif; ?>


    </div>
</div>








<div class="loader">

    <div class="place">
        <i class="anim glyphicons rotation_lock"></i><br/>

        <label><?php echo Yii::t('app', 'Поворачиваем...') ?></label>

    </div>
</div>








