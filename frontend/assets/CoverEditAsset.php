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
class CoverEditAsset extends AssetBundle
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
        'css/pb-layout.css',
        'css/pb-theme.css',
        //'css/custom.css',


    ];
    public $js = [

        'js/bootbox.js',
        'js/editable.js',
        'js/jquery.bootstrap-touchspin.min.js',
        'js/vendor/jquery.ui.widget.js',
        'js/jquery.iframe-transport.js',
        'js/jquery.fileupload.js',
        'js/jquery.fileupload-process.js',
        'js/jquery.fileupload-validate.js',
        'js/jquery.mCustomScrollbar.concat.min.js',
        'js/user-url.js',
        'js/loader-ui.js',
        'js/covers-api.js',
        'js/covers-edit.js',
        'js/holder.js'


    ];
    public $depends = [

        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];
}
