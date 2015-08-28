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
class TemplatesEditAsset extends AssetBundle
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
        'css/pb-layout.css',
        'css/pb-theme.css',
        'css/custom.css',
        'css/editor.css',
        'css/template.css?t=1'


    ];
    public $js = [

       /* 'js/svg/jquery.svg.js',
        'js/svg/jquery.svgdom.min.js',*/
        /*'js/raphael-min.js',
        'js/raphael.group.js',
        'js/raphael.free_transform.js',*/
      /*  'js/snap.svg-min.js',
        'js/snaptut-freetransform.js',*/

        'js/fabricjs.js',
        'js/fabricjs_viewport.js',


        'js/bootbox.js',
        'js/editable.js',
        'js/jquery.bootstrap-touchspin.min.js',
        'js/vendor/jquery.ui.widget.js',
        'js/jquery.iframe-transport.js',
        'js/jquery.fileupload.js',
        'js/jquery.fileupload-process.js',
        'js/jquery.fileupload-validate.js',
        'js/jquery.mCustomScrollbar.concat.min.js',
        'js/jquery.miniColors.js',
        'js/ladda.min.js',
        'js/loader-ui.js',
        'js/template-api.js',
        'js/template-edit.js',


    ];
    public $depends = [

        'yii\web\YiiAsset',

        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];
}
