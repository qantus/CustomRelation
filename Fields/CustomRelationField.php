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

namespace Modules\CustomRelation\Fields;

use Mindy\Base\Mindy;
use Mindy\Form\Fields\TextField;
use Mindy\Helper\Json;
use Mindy\Utils\RenderTrait;
use Modules\CustomRelation\Models\CustomRelation;

class CustomRelationField extends TextField
{
    use RenderTrait;

    public $field;

    public function renderInput()
    {
        list($choices, $all) = $this->makeChoices();
        $selected = $this->getSelected();

        return $this->renderTemplate('custom_relation/field.html', [
            'choices' => $choices,
            'all' => Json::encode($all),
            'name' => $this->getHtmlName(),
            'id' => $this->getHtmlId(),
            'field' => $this->field,
            'model' => $this->getForm()->getInstance(),
            'selected' => Json::encode($selected),
            'field_name' => $this->getName()
        ]);
    }

    public function getSelected()
    {
        $model = $this->getForm()->getInstance();
        $value = $this->getValue();
        if (!$value && $model->pk) {
            $data = CustomRelation::objects()->filter([
                'owner_pk' => $model->pk,
                'owner_class' => $model->className(),
                'field' => $this->field,
                'name' => $this->getName()
            ])->order(['position'])->valuesList(['related_class', 'related_pk']);

            $value = [];
            foreach($data as $item) {
                $value[] = $item['related_class'] . ':' . $item['related_pk'];
            }
        } elseif ($value) {
            $value = Json::decode($value);
        }
        return $value ? $value : [];
    }

    public function makeChoices()
    {
        $choices = [];
        $all = [];

        $fieldsConfig = Mindy::app()->getModule('CustomRelation')->fields;
        $fieldConfig = $fieldsConfig[$this->field];

        foreach($fieldConfig as $relatedClass => $config) {
            $qs = $relatedClass::objects()->getQuerySet();
            if (isset($config['filter'])) {
                $qs->filter($config['filter']);
            }
            if (isset($config['exclude'])) {
                $qs->exclude($config['exclude']);
            }
            if (isset($config['order'])) {
                $qs->order($config['order']);
            }

            $objects = $qs->all();
            $title = $config['title'];

            $data = [];
            foreach ($objects as $object) {
                $data[$relatedClass.':'.$object['id']] = (string) $object;
            }

            $all+=$data;

            $choices[] = [
                'title' => $title,
                'objects' => $data
            ];
        }

        return array($choices, $all);
    }
}