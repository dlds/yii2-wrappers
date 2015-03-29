<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2014 Digital Deals s.r.o. 
 * @license http://www.digitaldeals.cz/license/
 */

namespace dlds\wrappers\vimeo\components;

use yii\base\ErrorException;

/**
 * Action class for Sortable module
 */
class Action extends \yii\base\Action {

    public $id;

    public function run()
    {
        if (!$this->id)
        {
            throw new CException('Component name must be specified.');
        }

        $component = \Yii::$app->get($this->id);

        if (!$component)
        {
            throw new ErrorException('Yii::\$app has no component with id "' . $this->id . '".');
        }

        $id = \Yii::$app->request->get('id', false);

        if ($id)
        {
            echo \yii\helpers\Json::encode($component->getMedia($id));

            \Yii::$app->end();
        }

        $query = \Yii::$app->request->get('q', false);

        if ($query)
        {
            echo \yii\helpers\Json::encode($component->getMediaList(['query' => $query]));

            \Yii::$app->end();
        }
    }

}
