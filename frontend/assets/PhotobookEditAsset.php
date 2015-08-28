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
class PhotobookEditAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'glyphicons/css/glyphicons.css',
        'css/font-awesome.min.css',
        'css/jquery.bootstrap-touchspin.min.css',
        'css/jquery.miniColors.css',
        'css/jquery.mCustomScrollbar.min.css',
        'css/jquery.fileupload.css',
        'css/ladda-themeless.min.css',
        'css/slider.css',

        'css/bookblock.css',
        'css/custom.css',

        'css/pb-layout.css',
        'css/pb-theme.css',
        'css/editor.css',

    ];
    public $js = [

/*        'js/jquery-sortable.js',*/
        'js/jquery.dragsort-0.5.2.min.js',
        'js/jquery.mousewheel.js',
        'js/jquery.jscrollpane.min.js',
        'js/jquerypp.custom.js',
        'js/jquery.bookblock.js',
        'js/holder.js',
        'js/bootbox.js',
        'js/editable.js',
        'js/jquery.bootstrap-touchspin.min.js',
        'js/vendor/jquery.ui.widget.js',
        'js/jquery.iframe-transport.js',
        'js/jquery.fileupload.js',
        'js/jquery.mCustomScrollbar.concat.min.js',
        'js/bootstrap-slider.js',
        'js/spin.min.js',
        'js/ladda.min.js',
        'js/prism.js',
        'js/loader-ui.js',
        'js/user-url.js',
        'js/photos-api.js',
        'js/page.js',
        'js/editor.js',


    ];

    public $depends = [

        'yii\web\YiiAsset',
        'frontend\assets\UiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];
}
