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
class CartForm extends Model
{
    public $id = null;

    public $user_id;

    public $title = '';

    public $price;

    public $quantity;

    public $sub_total;

    public $product_type;

    public $product_info;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['title', 'product_type',   'product_info'], 'string'],
            ['title', 'filter', 'filter' => 'trim'],
            [[ 'user_id', 'quantity'], 'integer'],
            [['price', 'sub_total'], 'double'],
        ];
    }

    public function loadById($id){

        $cart=Cart::findOne(['id'=>$id]);
        if(!empty($cart)){
            $this->load( $cart->toArray(), '');


            $this->id=$id;

            $this->product_info=json_decode($this->product_info, true);



            return true;
        }

        return false;
    }











    public static function getUserCart($user_id, $asArray=false, $course=1){

        $rows=Cart::find()->where(['user_id'=>$user_id])->all();


        if($asArray){

            $new_rows=[];

            foreach($rows as $key=>$row){


                $new_rows[]=[

                    'title'=>$row->title,
                    'price'=>$row->price*$course,
                    'quantity'=>$row->quantity,
                    'sub_total'=>$row->sub_total*$course,
                    'product_type'=>$row->product_type,
                    'product_info'=>json_decode($row->product_info, true),

                ];

            }

            $rows=$new_rows;
        }


        return $rows;

    }




    public function save(){



         //Yii::getLogger()->log('start save photobook:'.$this->id, YII_DEBUG);

        $cart=Cart::findOne(['id'=>$this->id]);
        if(empty($cart)){

            $cart=new Cart();



            $cart->user_id=$this->user_id;

            $cart->title=$this->title;


            $cart->price=$this->price;


            $cart->quantity=$this->quantity;

            $cart->sub_total=$this->sub_total;

            $cart->product_type=$this->product_type;


            if(empty($this->product_info)){

                $this->product_info=[];
            }

            $cart->product_info=json_encode($this->product_info);







            Yii::getLogger()->log('save:', YII_DEBUG);
            if($cart->save()){

                $this->id=$cart->id;
                return $cart;
            }else{
                Yii::getLogger()->log('save error', YII_DEBUG);
            }

        }else{

            $cart->user_id=$this->user_id;

            $cart->title=$this->title;


            $cart->price=$this->price;


            $cart->quantity=$this->quantity;

            $cart->sub_total=$this->sub_total;

            $cart->product_type=$this->product_type;


            if(empty($this->product_info)){

                $this->product_info=[];
            }

            $cart->product_info=json_encode($this->product_info);


            Yii::getLogger()->log('update:', YII_DEBUG);

            if($cart->update()){
                return $cart;
            }else{


                Yii::getLogger()->log('update error:'.print_r( $cart, true), YII_DEBUG);
            }

        }


         return null;

    }


}
