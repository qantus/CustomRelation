<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 10/08/15 16:29
 */
namespace Modules\CustomRelation\Models;

use Mindy\Orm\Fields\CharField;
use Mindy\Orm\Fields\IntField;
use Mindy\Orm\Model;

class CustomRelation extends Model
{
    public static function getFields() 
    {
        return [
            'owner_class' => [
                'class' => CharField::className()
            ],
            'owner_pk' => [
                'class' => IntField::className()
            ],
            'related_class' => [
                'class' => CharField::className()
            ],
            'related_pk' => [
                'class' => IntField::className()
            ],
            'position' => [
                'class' => IntField::className(),
                'null' => true
            ],
            'field' => [
                'class' => CharField::className()
            ],
            'name' => [
                'class' => CharField::className()
            ]
        ];
    }
    
    public function __toString()
    {
        return (string) $this->related_class . ':' . $this->related_pk;
    }
} 