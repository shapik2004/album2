<?php

namespace common\components;

use app\components\UserUrl;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\log\Logger;
//use app\components\Box;


class Utils extends Component

{


    public static function recurse_copy($src,$dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    Utils::recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }


    public static function change_key($key,$new_key,&$arr,$rewrite=true){

        //Yii::getLogger()->log('chenge_key:'.print_r($arr, true), YII_DEBUG);

        $new_array=array();
        foreach($arr as $key2=>$val){
            $index=$key2;
            if($index==$key) $index=$new_key;


            $new_array[$index]=$val;
        }

        //Yii::getLogger()->log('chenge_key2:'.print_r($new_array, true), YII_DEBUG);
        return $new_array;
    }


    public static function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") Utils::rrmdir($dir."/".$object); else unlink($dir."/".$object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    public  static function moveElement(&$array, $a, $b) {
        $out = array_splice($array, $a, 1);
        array_splice($array, $b, 0, $out);
    }


    public  static function array_insert(&$array,$element,$position=null) {
        if (count($array) == 0) {
            $array[] = $element;
        }
        elseif (is_numeric($position) && $position < 0) {
            if((count($array)+$position) < 0) {
                $array = array_insert($array,$element,0);
            }
            else {
                $array[count($array)+$position] = $element;
            }
        }
        elseif (is_numeric($position) && isset($array[$position])) {
            $part1 = array_slice($array,0,$position,true);
            $part2 = array_slice($array,$position,null,true);
            $array = array_merge($part1,array($position=>$element),$part2);
            foreach($array as $key=>$item) {
                if (is_null($item)) {
                    unset($array[$key]);
                }
            }
        }
        elseif (is_null($position)) {
            $array[] = $element;
        }
        elseif (!isset($array[$position])) {
            $array[$position] = $element;
        }
        $array = array_merge($array);
        return $array;
    }


    public  static  function array_insert2(&$array, $value, $index)
    {
        return $array = array_merge(array_splice($array, max(0, $index - 1)), array($value), $array);
    }

    public  static function pages_filter($pages){

        foreach($pages as $key=>$page){

            unset($pages[$key]['svg_thumb']);
        }

        return $pages;
    }

    public  static function create_zip($files = array(),$destination = '',$overwrite = false, $sufix='_o.jpg', $ext='.jpg') {

        if(file_exists($destination) && !$overwrite) { return false; }

        $valid_files = array();

        if(is_array($files)) {

            foreach($files as $file) {

                if(file_exists($file)) {
                    $valid_files[] = $file;
                }
            }
        }

        if(count($valid_files)) {

            $zip = new \ZipArchive();
            if($zip->open($destination,$overwrite ? \ZipArchive::OVERWRITE : \ZipArchive::CREATE) !== true) {
                return false;
            }

            foreach($valid_files as $file) {
                $zip->addFile($file,basename($file, $sufix).$ext);
            }

            $zip->close();

            //Yii::getLogger()->log("ZIP:".$zip->getStatusString(), LOG_DEBUG);


            return file_exists($destination);
        }
        else
        {
            return false;
        }
    }



    public  static function makeTextBlock($text, $fontfile, $fontsize, $width)
    {
            $words = explode(' ', $text);
            $lines = array($words[0]);
            $currentLine = 0;
            for($i = 1; $i < count($words); $i++)
            {
                $lineSize = imagettfbbox($fontsize, 0, $fontfile, $lines[$currentLine] . ' ' . $words[$i]);
                if($lineSize[2] - $lineSize[0] < $width)
                {
                    $lines[$currentLine] .= ' ' . $words[$i];
                }
                else
                {
                    $currentLine++;
                    $lines[$currentLine] = $words[$i];
                }
            }

            return implode("\n", $lines);
    }


    public  static function mmToPx($value_mm, $dpi)
    {
            return round((($value_mm/10)/2.54)*$dpi);
    }

    public  static function pxToMm($value_px, $dpi)
    {
        return round((($value_px/$dpi)*2.54)*10);
    }

    public  static function ptToPx($value_pt, $dpi)
    {
            return round(($value_pt/72)*$dpi);
    }

    public  static function hexColorToArray($hex_color_str)
    {
            $hex = $hex_color_str;
            list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
            return [$r, $g, $b];
    }

    public  static  function makeTextImage($text, $place_width, $place_height, $text_color='#000000', $font=null, $margin=2, $dpi=300){


        $new_text=$text;

        if(empty($font))
        $font=UserUrl::style(false).DIRECTORY_SEPARATOR.'fonts'.DIRECTORY_SEPARATOR.'arial.ttf';


        $max_font_size=72; //pt
        $min_font_size=14; //pt

        $place_width_px=Utils::mmToPx($place_width, $dpi);
        $place_height_px=Utils::mmToPx($place_height, $dpi);
        $margin_px=Utils::mmToPx($margin, $dpi);
        $max_font_size_px=Utils::ptToPx($max_font_size, $dpi);

        $current_font_size=$max_font_size;
        $current_font_size_px=Utils::ptToPx($current_font_size, $dpi);
        $rect = imagettfbbox($current_font_size_px, 0, $font, $text);


        $width_px=$rect[2] - $rect[0];
        $height_px=$rect[1] - $rect[5];

        while($width_px+($margin_px*2)>$place_width_px || $height_px+($margin_px*2)>$place_height_px){

            $current_font_size--;
            //echo 'current_font_size='.$current_font_size;
            $current_font_size_px=Utils::ptToPx($current_font_size, $dpi);
            if($current_font_size<=$min_font_size  ){

                if($width_px+($margin_px*2)>$place_width_px)
                    $new_text=Utils::makeTextBlock($new_text, $font, $current_font_size_px, $place_width_px);
            }


            $rect = imagettfbbox($current_font_size_px, 0, $font, $new_text);

            //echo 'place_width_px='.$place_width_px.'<br/>';
            //echo 'place_height_px='.$place_height_px.'<br/>';
            //print_r($rect);
            $width_px=$rect[2] - $rect[0];
            $height_px=$rect[1] - $rect[5];

        }



        $im = imagecreatetruecolor($place_width_px, $place_height_px);
        $backgroundColor = imagecolorallocatealpha($im, 0, 0, 0, 127);
        imagefill($im, 0, 0, $backgroundColor);


        $box = new Box($im);
        $box->setFontFace($font); // http://www.dafont.com/franchise.font
        $box->setFontColor(Utils::hexColorToArray($text_color));

        $box->setFontSize($current_font_size_px);
        $box->setLeading(1);
        $box->setBox($margin_px, $margin_px, $place_width_px-($margin_px*2), $place_height_px-($margin_px*2));
        $box->setTextAlign('center', 'center');
        $box->draw($new_text);

       // header("Content-type: image/png");
        imagesavealpha($im, TRUE);



        ob_start(); //Stdout --> buffer

        imagepng($im);

        $img = ob_get_contents(); //store stdout in $img2

        ob_end_clean(); //clear buffer

        imagedestroy($im);

        return $img;
    }

    public static function getInclinationByNumber($number, $arr = Array()) {
        $number = (string) $number;
        $numberEnd = substr($number, -2);
        $numberEnd2 = 0;
        if(strlen($numberEnd) == 2){
            $numberEnd2 = $numberEnd[0];
            $numberEnd = $numberEnd[1];
        }

        if ($numberEnd2 == 1) return $arr[2];
        else if ($numberEnd == 1) return $arr[0];
        else if ($numberEnd > 1 && $numberEnd < 5)return $arr[1];
        else return $arr[2];
    }



    public static function timeAgo($time){


        $current_time=time();

        $diff=$current_time-$time;

        $sec=$diff;
        $min=intval($diff/60);
        $hour=intval($diff/60/60);
        $day=intval($diff/60/60/24);
        $month=intval($diff/60/60/24/31);
        $year=intval($diff/60/60/24/31/12);


        if($sec<60){

            return Yii::t('app', '{num} {unit} назад', ['num'=>$sec, 'unit'=>Utils::getInclinationByNumber($sec, ['сукунда', 'сукунды', 'сукунд'])]);
        }else if($min<60){


            return Yii::t('app', '{num} {unit} назад', ['num'=>$min, 'unit'=>Utils::getInclinationByNumber($min, ['минута', 'минуты', 'минут'])]);

        }else if($hour<24){


            return Yii::t('app', '{num} {unit} назад', ['num'=>$hour, 'unit'=>Utils::getInclinationByNumber($hour, ['час', 'часа', 'часов'])]);

        }else if($day<31){


            return Yii::t('app', '{num} {unit} назад', ['num'=>$day, 'unit'=>Utils::getInclinationByNumber($day, ['день', 'дня', 'дней'])]);

        }else if($month<12){


            return Yii::t('app', '{num} {unit} назад', ['num'=>$month, 'unit'=>Utils::getInclinationByNumber($month, ['месяц', 'месяца', 'месяцев'])]);

        }else{


            return Yii::t('app', '{num} {unit} назад', ['num'=>$year, 'unit'=>Utils::getInclinationByNumber($year, ['год', 'года', 'лет'])]);

        }




    }




}

