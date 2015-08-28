<?php
namespace frontend\models;

use common\models\UserSetting;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use app\components\UserUrl;
use app\components\AlphaId;


/**
 * UserSettingForm form
 */
class UserSettingForm extends Model
{
    public $user_id;
    public $color_1 = '#993149';
    public $color_2 = '#ffffff';
    public $primary_text_color = '#ffffff';
    public $primary_back_color = '#993149';
    public $primary_border_color = '#993149';
    public $primary_active_border_color = '#822432';
    public $primary_active_back_color = '#822432';
    public $primary_active_text_color = '#ffffff';

    public $default_text_color = '#993149';
    public $default_back_color = '#ffffff';
    public $default_border_color = '#993149';
    public $default_active_border_color = '#822432';
    public $default_active_back_color = '#FFF0F4';
    public $default_active_text_color = '#993149';

    public $link_color = '#993149';
    public $active_link_color = '#822432';

    public $css = '';

    public $logo_url = 'default-logo';

    public $css_file_id = 'pb-theme-color-default.css';



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            ['color_1', 'filter', 'filter' => 'trim'],
            ['color_1', 'required'],

            ['color_2', 'filter', 'filter' => 'trim'],
            ['color_2', 'required'],


            ['link_color', 'filter', 'filter' => 'trim'],
            ['link_color', 'required'],

            ['active_link_color', 'filter', 'filter' => 'trim'],
            ['active_link_color', 'required'],

            ['primary_text_color', 'filter', 'filter' => 'trim'],
            ['primary_text_color', 'required'],

            ['primary_back_color', 'filter', 'filter' => 'trim'],
            ['primary_back_color', 'required'],

            ['primary_border_color', 'filter', 'filter' => 'trim'],
            ['primary_border_color', 'required'],

            ['primary_active_back_color', 'filter', 'filter' => 'trim'],
            ['primary_active_back_color', 'required'],

            ['primary_active_text_color', 'filter', 'filter' => 'trim'],
            ['primary_active_text_color', 'required'],

            ['primary_active_border_color', 'filter', 'filter' => 'trim'],
            ['primary_active_border_color', 'required'],



            ['default_text_color', 'filter', 'filter' => 'trim'],
            ['default_text_color', 'required'],

            ['default_back_color', 'filter', 'filter' => 'trim'],
            ['default_back_color', 'required'],

            ['default_border_color', 'filter', 'filter' => 'trim'],
            ['default_border_color', 'required'],

            ['default_active_back_color', 'filter', 'filter' => 'trim'],
            ['default_active_back_color', 'required'],

            ['default_active_text_color', 'filter', 'filter' => 'trim'],
            ['default_active_text_color', 'required'],

            ['default_active_border_color', 'filter', 'filter' => 'trim'],
            ['default_active_border_color', 'required'],

            ['css', 'filter', 'filter' => 'trim'],
            ['css', 'required'],

            [['logo_url'],'file', 'skipOnEmpty' => true  ],


        ];
    }





    public function loadByUserId($user_id){

        $setting=UserSetting::findByUserId($user_id);
        if(!empty($setting)){
            $this->load( $setting->toArray(), '');
            return true;
        }

        return false;
    }



    public function save(){

        $old_setting=UserSetting::findByUserId($this->user_id);
        if(empty($old_setting)){

            $setting=new UserSetting();
            $setting->user_id=$this->user_id;

            $setting->color_1=$this->color_1;
            $setting->color_2=$this->color_2;

            $setting->primary_text_color=$this->primary_text_color;
            $setting->primary_back_color=$this->primary_back_color;
            $setting->primary_border_color=$this->primary_border_color;

            $setting->primary_active_border_color=$this->primary_active_border_color;

            $setting->primary_active_back_color=$this->primary_active_back_color;
            $setting->primary_active_text_color=$this->primary_active_text_color;


            $setting->default_text_color=$this->default_text_color;
            $setting->default_back_color=$this->default_back_color;
            $setting->default_border_color=$this->default_border_color;
            $setting->default_active_back_color=$this->default_active_back_color;
            $setting->default_active_text_color=$this->default_active_text_color;

            $setting->default_active_border_color=$this->default_active_border_color;

            $setting->active_link_color=$this->active_link_color;
            $setting->link_color=$this->link_color;

            $setting->css=$this->css;



            if( $this->logo_url)
            $setting->logo_url=$this->logo_url;
            else{
                unset($setting->logo_url);
            }

            $setting->css_file_id=$this->saveCss($setting->css);

            $setting->save();
            return $setting;

        }else{


            $old_setting->color_1=$this->color_1;
            $old_setting->color_2=$this->color_2;


            $old_setting->primary_text_color=$this->primary_text_color;
            $old_setting->primary_back_color=$this->primary_back_color;
            $old_setting->primary_border_color=$this->primary_border_color;

            $old_setting->primary_active_border_color=$this->primary_active_border_color;

            $old_setting->primary_active_back_color=$this->primary_active_back_color;
            $old_setting->primary_active_text_color=$this->primary_active_text_color;

            $old_setting->default_text_color=$this->default_text_color;
            $old_setting->default_back_color=$this->default_back_color;
            $old_setting->default_border_color=$this->default_border_color;
            $old_setting->default_active_back_color=$this->default_active_back_color;
            $old_setting->default_active_text_color=$this->default_active_text_color;

            $old_setting->default_active_border_color=$this->default_active_border_color;

            $old_setting->active_link_color=$this->active_link_color;
            $old_setting->link_color=$this->link_color;

            $old_setting->css=$this->css;

            if( $this->logo_url)
                $old_setting->logo_url=$this->logo_url;
            else{
                unset($old_setting->logo_url);
            }

            $old_css_file_id=$old_setting->css_file_id;

            $old_setting->css_file_id=$this->saveCss($old_setting->css);




            $old_setting->update();

            if(!empty($old_css_file_id) && $old_css_file_id!=='pb-theme-color-default.css'){

                $css_file_path=UserUrl::css().DIRECTORY_SEPARATOR.$old_css_file_id.'.css';
                if(file_exists($css_file_path)){

                    unlink($css_file_path);
                }
            }

            return $old_setting;
        }
        return null;

    }

    private function saveCss($css){

        $css_file_path=UserUrl::css(false, $this->user_id);

        $css_file_id=rand(10000000000, 99999999999999);
        $css_file_id=AlphaId::id($css_file_id);

        file_put_contents($css_file_path.DIRECTORY_SEPARATOR.$css_file_id.'.css', $css);




        return $css_file_id;

    }


}
