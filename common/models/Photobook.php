<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property integer $user_id
 * @property string  $logo_url
 * @property string  $custom_color
 */
class Photobook extends ActiveRecord
{
    const  STATUS_NEW = 1;
    const  STATUS_SENT_TO_CUSTOMER=2;
    const  STATUS_WAIT_EDIT_FROM_CUSTOMER=3;
    const  STATUS_SENT_TO_PRINT=4;
    const  STATUS_READY_FOR_PRINT_WAIT_PAYMENT=5;
    const  STATUS_READY_FOR_PRINT_PAID=6;
    const  STATUS_READY_SENT_TO_PRINT=7;
    const  STATUS_READY=8;
    const  STATUS_READY_SENT_TO_CLIENT=9;
    const  STATUS_RECEIVED_FEEDBACK=10;
    const  STATUS_ARCHIVE = 11;
    const  STATUS_DEMO=20;
    /*const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const ROLE_USER = 10;*/

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%photobook}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    /*public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],

            ['role', 'default', 'value' => self::ROLE_USER],
            ['role', 'in', 'range' => [self::ROLE_USER]],
        ];
    }*/

    /**
     * @inheritdoc
     */
    /*public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }*/

    /**
     * @inheritdoc
     */
    /*public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }*/



    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByUserId($user_id)
    {
        return static::findOne(['user_id' => $user_id]);
    }



    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }



}
