<?php
namespace common\models;

use common\models\UserSetting;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use app\components\UserUrl;
use app\components\AlphaId;
use common\models\User;


/**
 * UserSettingForm form
 */
class AdminUserEditForm extends Model
{

    public $id;
    public $username;
    public $email;
    public $password;
    public $default_currency;
    public $role;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            /*['username', 'unique', 'targetClass' => '\common\models\User', 'targetAttribute'=>['username'=>'username'], 'message' => Yii::t('app', 'Это имя пользователя уже занято.')],*/
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
           /* ['email', 'unique', 'targetClass' => '\common\models\User', 'targetAttribute'=>['email'=>'email'], 'message' =>  Yii::t('app', 'Этот email уже занят.')],*/


            ['password', 'string'],


            ['default_currency', 'filter', 'filter' => 'trim'],
            ['default_currency',  'string'],


            ['role',  'integer']





        ];
    }





    public function loadByUserId($user_id){

        $user=User::findOne(['id'=>$user_id]);
        if(!empty($user)){
            $this->load( $user->toArray(), '');
            $this->id=$user->id;
            return true;
        }

        return false;
    }



    public function save(){

       $user=User::findOne(['id'=>$this->id]);

       if(!empty($user)){

            //$user->id=Yii::$app->user->identity->getId();
            $user->username=$this->username;
            $user->email=$this->email;
            $user->default_currency=$this->default_currency;

            if(!empty($this->password)) {
                $user->setPassword($this->password);
            }
            //$user->generateAuthKey();

            if($user->update(false)){

                return $user;
            }else{

                return false;

            }



        }
        return null;

    }



}
