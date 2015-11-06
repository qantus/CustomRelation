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
 * @date 11/08/15 17:04
 */
namespace Modules\CustomRelation\Controllers;

use Mindy\Helper\Json;
use Modules\Core\Controllers\CoreController;
use Modules\CustomRelation\Models\CustomRelation;

class SetController extends CoreController
{
    public function actionSet()
    {
        $post = $this->request->post;

        $pk = $post->get('pk');
        $data = $post->get('data', []);
        $field = $post->get('field');
        $name = $post->get('name');
        $modelClass = $post->get('modelClass');
        if (!$data) {
            $data = [];
        };
        if ($pk && is_array($data) && $field && $name && $modelClass) {
            CustomRelation::objects()->filter([
                'owner_pk' => $pk,
                'owner_class' => $modelClass,
                'field' => $field,
                'name' => $name
            ])->delete();

            foreach($data as $position => $item) {
                list($relatedClass, $relatedPk) = explode(':', $item);
                $relation = new CustomRelation();
                $relation->owner_pk = $pk;
                $relation->owner_class = $modelClass;

                $relation->related_pk = $relatedPk;
                $relation->related_class = $relatedClass;

                $relation->field = $field;
                $relation->position = $position+1;
                $relation->name = $name;

                $relation->save();
            }
        }
    }
} 