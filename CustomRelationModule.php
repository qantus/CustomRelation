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
 * @date 10/08/15 16:25
 */
namespace Modules\CustomRelation;

use Mindy\Base\Mindy;
use Mindy\Base\Module;

class CustomRelationModule extends Module
{
    public $fields = [];

    public static function preConfigure()
    {
        $tpl = Mindy::app()->template;
        $tpl->addHelper('get_related_objects', ['Modules\CustomRelation\Helpers\CustomRelationHelper', 'getRelated']);
    }
}
