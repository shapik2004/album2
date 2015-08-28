<?php
namespace frontend\models;

use common\models\SettingForm;
use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $id;
    public $username;
    public $email;
    public $password;

    public $default_currency;
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
             ['email', 'unique', 'targetClass' => '\common\models\User', 'targetAttribute'=>['email'=>'email'], 'message' =>  Yii::t('app', 'Этот email уже занят.')],



            ['password', 'string', 'min'=>6],

            ['password',  'required'],


            ['default_currency', 'filter', 'filter' => 'trim'],
            ['default_currency',  'string']
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */

    public function signupDemo()
    {
        if ($this->validate()) {
            $user = User::findOne(['id'=>$this->id]);//new User();

           // $user->id=$this->id;
            $user->username = $this->username;
            $user->email = $this->email;
            $user->role=User::ROLE_USER;

            if(!empty($this->default_currency)){

                $user->default_currency=$this->default_currency;

            }else{


                $settingForm=new SettingForm();

                $default_currency=$settingForm->getValue('default_currency', 'UAH');


                $user->default_currency=$default_currency;

            }

            $user->setPassword($this->password);
            $user->generateAuthKey();
            return $user->update(false);

        }

        return null;
    }


    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->save();
            return $user;
        }

        return null;
    }
}
