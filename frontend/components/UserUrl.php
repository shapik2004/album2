<?php

namespace app\components;


use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\base\InvalidConfigException;

class UserUrl extends  Component
{
    const  IMAGE_THUMB      =   '_t';
    const  IMAGE_SMALL      =   '_s';
    const  IMAGE_MIDDLE     =   '_m';
    const  IMAGE_LARGE      =   '_l';
    const  IMAGE_XLARGE     =   '_xl';
    const  IMAGE_XXLARGE    =   '_xxl';
    const  IMAGE_ORIGINAL    =  '_o';

    static  $IMAGE_SIZE = [
        UserUrl::IMAGE_THUMB=>['width'=>0,'height'=>100, 'size'=>87.5],
        UserUrl::IMAGE_SMALL=>['width'=>300,'height'=>300, 'size'=>29],
        UserUrl::IMAGE_MIDDLE=>['width'=>700,'height'=>700, 'size'=>12.5],
        UserUrl::IMAGE_LARGE=>['width'=>1000,'height'=>1000, 'size'=>8.75],
        UserUrl::IMAGE_XLARGE=>['width'=>1500,'height'=>1500, 'size'=>5.8333],
        UserUrl::IMAGE_XXLARGE=>['width'=>2000,'height'=>2000, 'size'=>4.375],
        UserUrl::IMAGE_ORIGINAL=>['width'=>0,'height'=>0, 'size'=>1],
    ];


    public static function template($url){

        if($url){
            $base_url=Yii::getAlias('@web').'/'.'images'.'/'.'templates';
            return $base_url;
        }else{
            $base_path=Yii::getAlias('@webroot').DIRECTORY_SEPARATOR.'images';
            return UserUrl::createNonexistentDirInPath($base_path, 'templates');
        }

    }

    public static function templateThumb($url, $template_id){

        if($url){
            $base_url=UserUrl::template(true);
            return $base_url.'/'.'thumbs'.'/'.$template_id;
        }else{
            $base_path=UserUrl::template(false);
            return UserUrl::createNonexistentDirInPath($base_path, 'thumbs').DIRECTORY_SEPARATOR.$template_id;
        }
    }

    public static function fu2($url, $template_id, $type){

        if($url){
            $base_url=Yii::getAlias('@web').'/'.'uploads';
            return $base_url.'/'.'templates'.'/'.$template_id.'/'.'fu2'.'/'.$type.'.fu2';
        }else{
            $base_path=Yii::getAlias('@webroot').DIRECTORY_SEPARATOR.'uploads';
            return UserUrl::createNonexistentDirInPath($base_path, 'templates'.DIRECTORY_SEPARATOR.$template_id.DIRECTORY_SEPARATOR.'fu2').DIRECTORY_SEPARATOR.$type.'.fu2';
        }
    }

    public static function font($url){

        if($url){
            $base_url=Yii::getAlias('@web').'/'.'uploads';
            return $base_url.'/'.'fonts';
        }else{
            $base_path=Yii::getAlias('@webroot').DIRECTORY_SEPARATOR.'uploads';
            return UserUrl::createNonexistentDirInPath($base_path, 'fonts');
        }
    }


    public static function cover($url){

        if($url){
            $base_url=Yii::getAlias('@web').'/'.'uploads';
            return $base_url.'/'.'covers';
        }else{
            $base_path=Yii::getAlias('@webroot').DIRECTORY_SEPARATOR.'uploads';
            return UserUrl::createNonexistentDirInPath($base_path, 'covers');
        }
    }

    public static function coverPadded($url, $cover_id){

        if($url){
            $base_url=UserUrl::cover(true);
            return $base_url.'/'.$cover_id.'/'.'padded';
        }else{
            $base_path=UserUrl::cover(false);
            return UserUrl::createNonexistentDirInPath($base_path, $cover_id.DIRECTORY_SEPARATOR.'padded');
        }
    }

    public static function coverFront($url, $cover_id){

        if($url){
            $base_url=UserUrl::cover(true);
            return $base_url.'/'.$cover_id.'/'.'front';
        }else{
            $base_path=UserUrl::cover(false);
            return UserUrl::createNonexistentDirInPath($base_path, $cover_id.DIRECTORY_SEPARATOR.'front');
        }
    }


    public static function coverBack($url, $cover_id){

        if($url){
            $base_url=UserUrl::cover(true);
            return $base_url.'/'.$cover_id.'/'.'back';
        }else{
            $base_path=UserUrl::cover(false);
            return UserUrl::createNonexistentDirInPath($base_path, $cover_id.DIRECTORY_SEPARATOR.'back');
        }
    }



    public static function coverThumb($url, $cover_id){

        if($url){
            $base_url=UserUrl::cover(true);
            return $base_url.'/'.$cover_id.'/'.'thumb';
        }else{
            $base_path=UserUrl::cover(false);
            return UserUrl::createNonexistentDirInPath($base_path, $cover_id.DIRECTORY_SEPARATOR.'thumb');
        }
    }


    public static function style($url){

        if($url){
            $base_url=Yii::getAlias('@web').'/'.'uploads';
            return $base_url.'/'.'styles';
        }else{
            $base_path=Yii::getAlias('@webroot').DIRECTORY_SEPARATOR.'uploads';
            return UserUrl::createNonexistentDirInPath($base_path, 'styles');
        }
    }


    public static function stylePaddedPassepartout($url, $style_id){


        if($url){
            $base_url=UserUrl::style(true);
            return $base_url.'/'.'passepartout'.'/'.$style_id;
        }else{
            $base_path=UserUrl::style(false);
            return UserUrl::createNonexistentDirInPath($base_path, 'passepartout'.DIRECTORY_SEPARATOR.$style_id);
        }
    }










    public static function styleThumb($url, $style_id){


        if($url){
            $base_url=UserUrl::style(true);
            return $base_url.'/'.'thumbs'.'/'.$style_id;
        }else{
            $base_path=UserUrl::style(false);
            return UserUrl::createNonexistentDirInPath($base_path, 'thumbs'.DIRECTORY_SEPARATOR.$style_id);
        }
    }

    public static function styleBackground($url, $style_id){


        if($url){
            $base_url=UserUrl::style(true);
            return $base_url.'/'.'backgrounds'.'/'.$style_id;
        }else{
            $base_path=UserUrl::style(false);
            return UserUrl::createNonexistentDirInPath($base_path, 'backgrounds'.DIRECTORY_SEPARATOR.$style_id);
        }
    }


    /*public static function styleThumb($url){


        if($url){
            $base_url=UserUrl::style(true);
            return $base_url.'/'.'thumb';
        }else{
            $base_path=UserUrl::style(false);
            return UserUrl::createNonexistentDirInPath($base_path, 'thumb');
        }
    }*/

    public static function photobook($url, $photobook_id, $user_id=null){

        if( Yii::$app->user->isGuest && $user_id==null)
            return false;

        if($user_id==null)
        {
            $user_id=Yii::$app->user->identity->getId();
        }

        //$alpha_id=AlphaId::id($user_id);
        $photobook_id=AlphaId::id($photobook_id);

        if($url){
            $base_url=UserUrl::home(true, $user_id).'/'.'pb';
            return $base_url.'/'.$photobook_id;
        }else{
            $base_path=UserUrl::home(false, $user_id);
            return UserUrl::createNonexistentDirInPath($base_path, 'pb'.DIRECTORY_SEPARATOR.$photobook_id);
        }


    }

    public static function photobookLayouts($url, $photobook_id, $user_id=null){

        if( Yii::$app->user->isGuest && $user_id==null)
            return false;

        if($user_id==null)
        {
            $user_id=Yii::$app->user->identity->getId();
        }

        //$alpha_id=AlphaId::id($user_id);


        if($url){
            $base_url=UserUrl::photobook(true, $photobook_id, $user_id);
            return $base_url.'/'.'layouts';
        }else{
            $base_path=UserUrl::photobook(false, $photobook_id, $user_id);
            return UserUrl::createNonexistentDirInPath($base_path, 'layouts');
        }

    }

    public static function photobookTexts($url, $photobook_id, $user_id=null){

        if( Yii::$app->user->isGuest && $user_id==null)
            return false;

        if($user_id==null)
        {
            $user_id=Yii::$app->user->identity->getId();
        }

        //$alpha_id=AlphaId::id($user_id);


        if($url){
            $base_url=UserUrl::photobook(true, $photobook_id, $user_id);
            return $base_url.'/'.'texts';
        }else{
            $base_path=UserUrl::photobook(false, $photobook_id, $user_id);
            return UserUrl::createNonexistentDirInPath($base_path, 'texts');
        }


    }


    public static function photobookWindowText($url, $photobook_id, $user_id=null){

        if( Yii::$app->user->isGuest && $user_id==null)
            return false;

        if($user_id==null)
        {
            $user_id=Yii::$app->user->identity->getId();
        }

        //$alpha_id=AlphaId::id($user_id);


        if($url){
            $base_url=UserUrl::photobook(true, $photobook_id, $user_id);
            return $base_url.'/'.'window_text';
        }else{
            $base_path=UserUrl::photobook(false, $photobook_id, $user_id);
            return UserUrl::createNonexistentDirInPath($base_path, 'window_text');
        }


    }


    public static function photobookTracingText($url, $photobook_id, $user_id=null){

        if( Yii::$app->user->isGuest && $user_id==null)
            return false;

        if($user_id==null)
        {
            $user_id=Yii::$app->user->identity->getId();
        }

        //$alpha_id=AlphaId::id($user_id);


        if($url){
            $base_url=UserUrl::photobook(true, $photobook_id, $user_id);
            return $base_url.'/'.'tracing';
        }else{
            $base_path=UserUrl::photobook(false, $photobook_id, $user_id);
            return UserUrl::createNonexistentDirInPath($base_path, 'tracing');
        }


    }




    public static function photobookPhotos($url, $photobook_id, $user_id=null){

        if( Yii::$app->user->isGuest && $user_id==null)
            return false;

        if($user_id==null)
        {
            $user_id=Yii::$app->user->identity->getId();
        }

        //$alpha_id=AlphaId::id($user_id);


        if($url){
            $base_url=UserUrl::photobook(true, $photobook_id, $user_id);
            return $base_url.'/'.'photos';
        }else{
            $base_path=UserUrl::photobook(false, $photobook_id, $user_id);
            return UserUrl::createNonexistentDirInPath($base_path, 'photos');
        }


    }


    public static function photobookPageThumb($url, $photobook_id, $user_id=null){

        if( Yii::$app->user->isGuest && $user_id==null)
            return false;

        if($user_id==null)
        {
            $user_id=Yii::$app->user->identity->getId();
        }

        //$alpha_id=AlphaId::id($user_id);


        if($url){
            $base_url=UserUrl::photobook(true, $photobook_id, $user_id);
            return $base_url.'/'.'pages_thumb';
        }else{
            $base_path=UserUrl::photobook(false, $photobook_id, $user_id);
            return UserUrl::createNonexistentDirInPath($base_path, 'pages_thumb');
        }


    }

    public static function home($url=false, $user_id=null)
    {
        if( Yii::$app->user->isGuest && $user_id==null)
            return false;

        if($user_id==null)
        {
            $user_id=Yii::$app->user->identity->getId();
        }

        $alpha_id=AlphaId::id($user_id);
        if($url){
            $base_url=Yii::getAlias('@web').'/'.'uploads';
            return $base_url.'/'.$alpha_id;
        }else{
            $base_path=Yii::getAlias('@webroot').DIRECTORY_SEPARATOR.'uploads';
            return UserUrl::createNonexistentDirInPath($base_path, $alpha_id);
        }
    }

    public static function logo($url=false, $user_id=null)
    {
        if( Yii::$app->user->isGuest && $user_id==null)
            return false;

        if($user_id==null)
        {
            $user_id=Yii::$app->user->identity->getId();
        }

        $alpha_id=AlphaId::id($user_id);
        if($url){
            $base_url=Yii::getAlias('@web').'/'.'uploads';
            return $base_url.'/'.$alpha_id.'/logo';
        }else{
            $base_path=Yii::getAlias('@webroot').DIRECTORY_SEPARATOR.'uploads';
            return UserUrl::createNonexistentDirInPath($base_path, $alpha_id.DIRECTORY_SEPARATOR.'logo');
        }
    }

    public static function logoUrl($image_id, $image_size=UserUrl::IMAGE_ORIGINAL, $ext='jpg', $user_id=null ){

        if( Yii::$app->user->isGuest && $user_id==null)
            return Yii::getAlias('@web').'/images/default-logo.png';


        if($user_id==null)
            $user_id=Yii::$app->user->identity->getId();

        if($image_id!=='default-logo'){

            $alpha_id=AlphaId::id($user_id);
            return Yii::getAlias('@web').'/uploads/'.$alpha_id.'/logo/'.UserUrl::imageFile($image_id, $image_size, $ext);
        }else{

            return Yii::getAlias('@web').'/images/default-logo.png';
        }
    }

    public static function imageFile($image_id, $image_size=UserUrl::IMAGE_ORIGINAL, $ext='jpg' ){

        return $image_id.$image_size.'.'.$ext;
    }


    public static function fontFile($font_id ){

        return $font_id;
    }

    public static function zipPhotosFile($id){

        return AlphaId::id($id).'.'.'zip';
    }

    public static function css($url=false, $user_id=null)
    {
        if( Yii::$app->user->isGuest && $user_id==null)
            return Yii::getAlias('@web').'/css';

        if($user_id==null)
        {
            $user_id=Yii::$app->user->identity->getId();
        }

        $alpha_id=AlphaId::id($user_id);
        if($url){
            $base_url=Yii::getAlias('@web').'/'.'uploads';
            return $base_url.'/'.$alpha_id.'/css';
        }else{
            $base_path=Yii::getAlias('@webroot').DIRECTORY_SEPARATOR.'uploads';
            return UserUrl::createNonexistentDirInPath($base_path, $alpha_id.DIRECTORY_SEPARATOR.'css');
        }
    }


    public static function cssUrl($css_file_id,  $user_id=null){

        if( Yii::$app->user->isGuest && $user_id==null)
            return Yii::getAlias('@web').'/css/pb-theme-color-default.css';


        if($user_id==null)
            $user_id=Yii::$app->user->identity->getId();

        if($css_file_id!=='default-logo.png' && !empty($css_file_id)){

            return UserUrl::css(true, $user_id) . '/'.$css_file_id . '.css';
        }else{

            return Yii::getAlias('@web').'/css/pb-theme-color-default.css';
        }
    }



    public static function createNonexistentDirInPath($basePath, $path){

        try{
            $pathElements=explode(DIRECTORY_SEPARATOR, $path);

            if(!is_array($pathElements)){
                $pathElements=[$path];
            }

            $current='';
            foreach($pathElements as $key=>$pathElement){

                $current.=DIRECTORY_SEPARATOR.$pathElement;

                if(!file_exists($basePath.$current)){

                    $createPath=$basePath.$current;
                    Yii::getLogger()->log('currentPath:'.$createPath, YII_DEBUG);
                    mkdir($createPath);

                }
            }

            return $basePath.DIRECTORY_SEPARATOR.$path;
        }catch(Exception $error){

            return false;
        }
    }




}