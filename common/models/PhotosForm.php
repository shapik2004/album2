<?php
namespace common\models;


use Yii;
use yii\base\Model;

use common\models\Photobook;


/**
 * Photobook form
 */
class PhotosForm extends Model
{

    public $photos;
    public $photobook_id;
    public $group;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['photos'],'file' ],
            ['group', 'filter', 'filter' => 'trim'],
        ];
    }



}
