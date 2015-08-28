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


/* @var $this \yii\web\View */
/* @var $content string */

//PhotobookAsset::register($this);
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


   <!-- <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.5/angular.min.js"></script>
    <script src="/js/fabricjs.js"></script>-->


    <link href="<?php echo UserUrl::cssUrl($this->params['css_file_id']); ?>" rel="stylesheet">

    <script src="/frontend/web/glyphicons/scripts/modernizr.js"></script>

</head>
<body class="fixed-size" data-demo="<?php if($this->params['demo']) echo '1'; else echo '0'; ?>">


    <?php $this->beginBody() ?>


    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </div>
    </div>



    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
