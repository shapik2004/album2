<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class IntroAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'glyphicons/css/glyphicons.css',
        'css/font-awesome.min.css',

        'css/jquery.miniColors.css',
        'css/jquery.mCustomScrollbar.min.css',
        'css/jquery.fileupload.css',
        'css/pb-layout.css',
        'css/pb-theme.css',


    ];
    public $js = [

        'js/bootbox.js',
        'js/holder.js',

        'js/photos-api.js',
        'js/loader-ui.js',
        'js/intro.js',


    ];
    public $depends = [

        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];
}
