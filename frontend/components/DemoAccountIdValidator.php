<?php
namespace app\components;

use common\models\User;
use yii\validators\Validator;
use Yii;


class DemoAccountIdValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {


        Yii::getLogger()->log('GLOBAL_TEST1'.print_r($model->$attribute, true), YII_DEBUG);

        $user=User::findOne(['id'=>$model->$attribute]);

        if(empty($user)){

            $this->addError($model, $attribute, 'Аккаунт с данным id не найден');
        }elseif($user->role==User::ROLE_ADMIN){


            $this->addError($model, $attribute, 'Не рекомендуется использовать аккаунт супер администратора');
        }

    }
}