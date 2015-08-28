<?php
namespace common\models;

use frontend\models\UserSettingForm;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app', 'Не верный email или пароль.'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }



    public function demoLogin()
    {
        //if ($this->validate()) {
            return Yii::$app->user->login($this->getDemoUser(),0);
        /*} else {
            return false;
        }*/
    }



    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
            if(!$this->_user)
                $this->_user = User::findByEmail($this->username);
        }

        return $this->_user;
    }

    /**
     * Finds user by [[ROLE_DEMO]]
     *
     * @return User|null
     */
    public function getDemoUser()
    {
        if ($this->_user === false) {

            //$this->_user = User::findOne(['role' => User::ROLE_DEMO]);

            //return $this->_user;

            $settingForm=new SettingForm();


            $demo_account_id=$settingForm->getValue('demo_account_id', 0);
            $default_currency=$settingForm->getValue('default_currency','UAH');

            if($demo_account_id) {

                $user= User::findOne(['id' => $demo_account_id]);

                if($user){


                    $new_demo_user=new User();

                   // $new_demo_user->id=0;

                    $new_demo_user->username=$user->username;

                   // $new_demo_user->auth_key=$user->auth_key;

                    $new_demo_user->email=md5(time().rand(0,99999999999999999999)).'@sensation.com.ua';//$user->email;

                    $new_demo_user->role=User::ROLE_DEMO;

                    $new_demo_user->status=User::STATUS_ACTIVE;//$user->status;

                    $new_demo_user->default_currency=$default_currency;//$user->default_currency;

                    $new_demo_user->setPassword('123456');

                    $new_demo_user->generateAuthKey();

                    if($new_demo_user->save(false)){


                        //Нужно скопирывать фотокнигу

                        $settingUserForm=new UserSettingForm();

                        $settingUserForm->user_id=$new_demo_user->id;

                        $settingUserForm->save();



                        /*
                         *
                         *
                         * $settingForm=new SettingForm();


                         $demo_account_id=$settingForm->getValue('demo_account_id', 0);

                        $photobooks=Photobook::find()->where(['status'=>Photobook::STATUS_NEW])->all();


                        if(count($photobooks)>0){


                            $photobook=$photobooks[0];


                            $photobookForm=new PhotobookForm();



                            if($photobookForm->loadById($photobook->id)){


                                $photobookForm->copyToUser($new_demo_user->id);


                            }


                        }*/



                        $this->_user=$new_demo_user;





                    }


                }

                //$this->_user = User::findOne(['role' => User::ROLE_DEMO]);

            }
            /*if(!$this->_user)
                $this->_user = User::findByEmail($this->username);*/
        }

        return $this->_user;
    }

}
