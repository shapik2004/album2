<?php


namespace app\components;


use common\models\SettingForm;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\base\InvalidConfigException;

class CurrencyConvertor extends  Component
{

    public static function conv($value,  $to = null, $from = null)
    {


        $settings=new SettingForm();


        $currencies=$settings->getValue('currencies', null);


        $main_currency=$settings->getValue('main_currency', null);



        if(empty($currencies)){

            return  $value;
        }



        if(empty($main_currency)){

            return  $value;
        }


        if($from==null){

            $from=$main_currency;
        }


        if($from!=$main_currency){

            $curse=1;


            foreach($currencies as $key=>$currency){

                if($currency['code']==$from){

                    $curse=$currency['value'];
                    break;
                    //$value=$value/
                }

            }

            $value=$value/$curse;
        }


        $curse=1;

        foreach($currencies as $key=>$currency){

            if($currency['code']==$to){

                $curse=$currency['value'];
                break;
                //$value=$value/
            }

        }


        $value=$value*$curse;

        return $value;
    }


    public static function getCurse($currency){



        $settings=new SettingForm();


        $currencies=$settings->getValue('currencies', null);


        if(empty($currencies)){

            return  1;
        }


        foreach($currencies as $key=>$currencyObj){

            if($currencyObj['code']==$currency){

                return $currencyObj['value'];

                //$value=$value/
            }

        }


        return 1;


    }


}

