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

$this->title = 'Фотокнига';

$this->title = Yii::t('app','Настройки');
$this->params['breadcrumbs'][]= Yii::t('app', 'Настройки');
$this->params['breadcrumbs'][] = $this->title;

/*echo $work_orders[0]->name;
print_r($work_orders);*/


use frontend\assets\PhotobooksIndexAsset;
PhotobooksIndexAsset::register($this);


?>


<div class="photobook-index">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">


            </div>
        </div>



        <div  class="row">
            <div class="col-xs-3" style="padding-top: 17px;">
                <div class="list-group">
                    <?php foreach($sidemenus as $key=>$menuitem): ?>

                        <a href="<?php echo $menuitem['url']; ?>" class="list-group-item <?php if($menuitem['active']) echo 'active'; ?>">

                            <?php echo $menuitem['title'] ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-xs-9">


                <div class="row">
                    <div class="col-xs-12">
                        <h2 ><?= Html::encode($this->title); ?></h2>
                    </div>
                </div>
                <hr />



            </div>
        </div>
    </div>





</div>


