<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

use yii\helpers\Url;

use app\components\UserUrl;

use  common\components\Utils;

use app\components\CurrencyConvertor;

use frontend\assets\LiqpayAsset;

use common\models\Invoice;





LiqpayAsset::register($this);

$this->title = Yii::t('app','Пожалуйста подождите');
$this->params['breadcrumbs'][] = $this->title;
?>

<div style="display: none;">
    <?php echo $form; ?>
</div>



<div class="start-loader">
    <div class="place">
        <i class="anim glyphicons repeat"></i><br/>

        <label><?php echo Yii::t('app', 'Пожалуйста подождите...') ?></label>

    </div>
</div>