<?php
namespace common\models;


use Yii;
use yii\base\Model;

use common\models\Style;
use common\models\Setting;
use common\models\Template;
use frontend\widgets\ThumbInGroup;
use common\components\Utils;
use app\components\UserUrl;
use app\components\AlphaId;
use yii\helpers\Url;
use frontend\widgets\UploadPhotosGroup;
use yii\web\UploadedFile;
use frontend\widgets\StyleLayoutGroup;


/**
 * Cover form
 */
class SettingForm extends Model
{
    public $id = null;

    public $name = '';

    public $value = "[]";

    private $_value_rules=[[['value'], 'string']];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $result= [
            [['name'], 'string'],
            ['name', 'filter', 'filter' => 'trim']
        ];

        foreach($this->_value_rules as $key=>$rule) {
            $result[] = $rule;
        }
        return $result;


    }

    public function setValueRules($rules){

        $this->_value_rules=$rules;
    }


    public function setValue($name, $value){

        if($this->loadByName($name)){

            $this->value=$value;


            if($this->save()){

                return true;

            }else{

                return false;

            }

        }else{

            $this->id=null;

            $this->name=$name;

            $this->value=$value;

            if($this->save()){

                return true;

            }else{

                return false;

            }

        }

    }


    public function getValue($name, $value, $default=null){


        if($this->loadByName($name)){

           return $this->value;

        }else{

            return $default;

        }

    }





    public function loadByName($name){

        $setting=Setting::findOne(['name'=>$name]);
        if(!empty($setting)){

            $oldRules=$this->_value_rules;

            $this->_value_rules=[[['value'], 'string']];


            $this->load( $setting->toArray(), '');

            $this->id=$setting->id;
            $this->name=$name;

            $this->value=SettingForm::decodeValue($this->value);



            $this->_value_rules=$oldRules;
            return true;
        }

        return false;
    }










    public function save(){


        $setting=Setting::findOne(['id'=>$this->id]);

        if(empty($setting)){

            $setting=new Setting();

            $setting->name=$this->name;
            $setting->value=SettingForm::encodeValue($this->value);

            $oldRules=$this->_value_rules;

            $this->_value_rules=[[['value'], 'string']];

            Yii::getLogger()->log('save:', YII_DEBUG);
            if($setting->save()){

                $this->_value_rules=$oldRules;
                $this->id=$setting->id;
                return $setting;
            }else{

                $this->_value_rules=$oldRules;
                Yii::getLogger()->log('save error', YII_DEBUG);
            }



        }else{

            $setting->name=$this->name;

            $setting->value=SettingForm::encodeValue($this->value);;

            $oldRules=$this->_value_rules;


            $this->_value_rules=[[['value'], 'string']];

            Yii::getLogger()->log('update:', YII_DEBUG);

            if($setting->update()){

                $this->_value_rules=$oldRules;
                return $setting;
            }else{

                $this->_value_rules=$oldRules;

                Yii::getLogger()->log('update error:'.print_r( $setting, true), YII_DEBUG);
            }



        }


         return null;

    }

    public static function encodeValue($value){


        return json_encode(['value'=>$value]);
    }

    public static function decodeValue($value){

        $v=json_decode($value, true);

        if(!empty($v) && !empty($v['value'])) {
            return $v['value'];
        }else{

            return '';
        }

    }


}
