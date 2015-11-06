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
 * @date 12/08/15 07:22
 */
namespace Modules\CustomRelation\Helpers;

use Modules\CustomRelation\Models\CustomRelation;

class CustomRelationHelper
{
    public static function getRelated($field, $model, $limit = 10, $offset = 0, $name = null)
    {
        $filter = [
            'owner_pk' => $model->pk,
            'owner_class' => $model->className(),
            'field' => $field
        ];

        if ($name) {
            $filter['name'] = $name;
        }

        $relations = CustomRelation::objects()->filter($filter)->order(['position'])
            ->limit($limit)->offset($offset)
            ->valuesList(['related_pk', 'related_class']);

        $objects = self::relationsToObjects($relations);

        return $objects;
    }

    public static function relationsToObjects($relations)
    {
        $grouped = [];
        foreach($relations as $relation) {
            if (!isset($grouped[$relation['related_class']])) {
                $grouped[$relation['related_class']] = [];
            }
            $grouped[$relation['related_class']][] = $relation['related_pk'];
        }

        $selected = [];
        foreach($grouped as $className => $pkList) {
            $selected = array_merge($selected, $className::objects()->filter(['pk__in' => $pkList])->all());
        }

        $result = [];
        foreach($relations as $relation) {
            foreach($selected as $key => $object) {
                if ($relation['related_class'] == $object->className() && $relation['related_pk'] == $object->id) {
                    $result[] = $object;
                    unset($selected[$key]);
                    break;
                }
            }
        }

        return $result;
    }
}