<?php
namespace common\models;


use Yii;
use yii\base\Model;

use common\models\Style;
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
class InvoiceForm extends Model
{
    public $id = null;

    public $user_id;

    public $status = Invoice::STATUS_NEW;

    public $data;

    public $payment_type;

    public $currency;

    public $total;

    public $delivery_address;




    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [


            [['data', 'payment_type',   'currency', 'delivery_address'], 'string'],
            ['delivery_address', 'filter', 'filter' => 'trim'],
            [[ 'status', 'user_id'], 'integer'],
            [['total'], 'double'],




        ];
    }



    public function loadById($id){

        $invoice=Invoice::findOne(['id'=>$id]);
        if(!empty($invoice)){
            $this->load( $invoice->toArray(), '');


            $this->id=$id;
            $this->data=json_decode($this->data, true);


            return true;
        }

        return false;
    }





    public function save(){



        $invoice=Invoice::findOne(['id'=>$this->id]);
        if(empty($invoice)){

            $invoice=new Invoice();


            $invoice->user_id=$this->user_id;

            $invoice->status=$this->status;

            $invoice->data=json_encode($this->data);

            $invoice->payment_type=$this->payment_type;

            $invoice->currency=$this->currency;

            $invoice->total=$this->total;

            $invoice->delivery_address=$this->delivery_address;








            Yii::getLogger()->log('save:', YII_DEBUG);
            if($invoice->save()){

                $this->id=$invoice->id;
                return $invoice;
            }else{
                Yii::getLogger()->log('save error', YII_DEBUG);
            }

        }else{

            $invoice->user_id=$this->user_id;

            $invoice->status=$this->status;

            $invoice->data=json_encode($this->data);

            $invoice->payment_type=$this->payment_type;

            $invoice->currency=$this->currency;

            $invoice->total=$this->total;

            $invoice->delivery_address=$this->delivery_address;

            Yii::getLogger()->log('update:', YII_DEBUG);

            if($invoice->update()){
                return $invoice;
            }else{


                Yii::getLogger()->log('update error:'.print_r( $invoice, true), YII_DEBUG);
            }

        }


         return null;

    }


}
