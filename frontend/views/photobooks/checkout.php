<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

use yii\helpers\Url;

use app\components\UserUrl;

use  common\components\Utils;

use app\components\CurrencyConvertor;

use  common\models\Photobook;



use frontend\assets\CheckoutAsset;
CheckoutAsset::register($this);

$this->title = Yii::t('app','Оформление заказа');
$this->params['breadcrumbs'][] = $this->title;
?>


<nav id="navbar" class="navbar navbar-default" role="navigation">

    <div class="container-fluid ">




        <div class="row">
            <div class="col-lg-5  col-md-3 ">
                <div class="navbar-header">
                    <a class="navbar-brand" >Оформление заказа <b>Шаг 2.</b></a>
                </div>
            </div>
            <div class="col-lg-7 col-md-9  header-buttons">


                <div class="navbar-form navbar-right" >


                    <div class="btn-group">

                       <!-- <a class="btn btn-gray button-2-line btnMakeLayoutsZip" data-url=" echo Url::toRoute(['photobook-api/download-layouts', 'id'=>$id, 'ref'=>$ref]);" >
                            <div class="button-col button-icon ">
                                <i class="glyphicons compressed"></i>
                            </div>
                            <div class="button-col">
                                <?php echo Yii::t('app', 'Скачать zip-архив <br/>с макетами '); ?>
                            </div>


                        </a>-->

                    </div>
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


        <h3><?php echo Yii::t('app', 'Ваш заказ:') ?></h3>
        <div class="table-responsive">
            <table id="totalCart" class="table" data-course="<?php echo $course; ?>" data-currency="<?php echo  $default_currency; ?>" >
                <thead>

                    <th>№</th>
                    <th><?php echo Yii::t('app', 'Наименование') ?></th>
                    <th  align="right" style="text-align: right;"><?php echo Yii::t('app', 'Цена') ?></th>
                    <th style="text-align: center;"><?php echo Yii::t('app', 'Количество экземпляров') ?></th>
                    <th  align="right" style="text-align: right;"><?php echo Yii::t('app', 'Сумма') ?></th>
                    <th  align="right" style="text-align: right;"><?php echo Yii::t('app', 'Действия') ?></th>
                </thead>

                <tbody>


                    <?php $total=0; ?>
                    <?php foreach($cart_rows as $key=>$row): ?>
                    <tr class="cart-rows cart-rows-<?php echo $row->id;  ?>" data-id="<?php echo $row->id;  ?>"   data-subtotal="<?php echo $row->sub_total; ?>"   >
                        <td class="row-index" width="70">
                            <?php echo $key+1; ?>
                        </td>
                        <td width="30%">
                            <?php echo $row->title; ?>
                        </td>
                        <td align="right" width="150">
                            <?php  printf('%.2f', CurrencyConvertor::conv($row->price, $default_currency)); ?> <?php echo $default_currency; ?>
                        </td>
                        <td width="100" style="text-align: center;"><input class="inputQuantity" data-id="<?php echo $row->id;  ?>" data-value="<?php echo $row->quantity; ?>" data-url="<?php echo  Url::toRoute(['photobook-api/update-cart-quantity', 'id'=>$row->id]); ?>"  type="text" style="text-align: center;" value="<?php echo $row->quantity; ?>" />

                        </td>
                        <td width="150" class="subtotal subtotal-<?php echo $row->id; ?>" align="right">
                            <?php $total+=($row->price*$row->quantity); ?>
                            <?php  printf('%.2f', CurrencyConvertor::conv($row->price*$row->quantity, $default_currency)); ?> <?php echo $default_currency; ?>
                        </td>
                        <td style="text-align: right;" width="70">
                            <a href="#" class="btnDeleteCartRow" data-id="<?php echo $row->id; ?>" data-url="<?php echo  Url::toRoute(['photobook-api/delete-cart-row', 'id'=>$row->id]); ?>"><?php echo Yii::t('app', 'Удалить'); ?></a>
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
                            <?php  printf('%.2f', CurrencyConvertor::conv($total, $default_currency)); ?> <?php echo $default_currency; ?>
                        </td>
                        <td></td>
                    </tr>
                </tbody>

            </table>
        </div>


        <h3><?php echo Yii::t('app', 'Адрес доставки и телефон контактного лица:') ?></h3>
        <textarea rows="6" data-url="<?php echo  Url::toRoute(['photobook-api/update-delivery-address']); ?>" id="deliveryAddress" class="form-control"><?php echo Yii::$app->user->identity->delivery_address; ?></textarea>
        <div class="help-block"><?php echo Yii::t('app', 'Укажите адрес доставки в формате ФИО, телефон, область, населенный пункт, улица. Доставка осуществляется "Новой почтой"') ?></div>




        <br/>  <br/>

        <div class="pull-right">


            <a href="<?php echo Url::toRoute(['photobooks/index', 'status'=>Photobook::STATUS_READY_FOR_PRINT_WAIT_PAYMENT])  ?>"  class="turn-on-editable btn btn-gray btn  button-2-line btnCancel"
               data-url="<?php echo Url::toRoute(['photobooks/index']); ?>" style="min-height: 55px" >



                            <span class="button-col" style="padding-top:7px;">
                            <?php echo Yii::t('app', 'Вернуться и создать еще одну фотокнигу'); ?>
                            </span>
            </a>


            <a class="turn-on-editable btn btn-primary button-2-line" href="<?php echo Url::toRoute(['photobooks/checkout2']); ?>"  >

                            <span class="button-col button-icon">
                                <i class="fa fa-shopping-cart"></i>
                            </span>
                            <span class="button-col" style="padding-top:7px;">
                            <?php echo Yii::t('app', 'Продолжить'); ?>
                            </span>
            </a><br/><br/><br/>
        </div>


    </div>
</div>








<div class="loader">

    <div class="place">
        <i class="anim glyphicons rotation_lock"></i><br/>

        <label><?php echo Yii::t('app', 'Поворачиваем...') ?></label>

    </div>
</div>








