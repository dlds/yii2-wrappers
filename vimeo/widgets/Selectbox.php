<?php

/**
 * @copyright Copyright (c) 2014 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace dlds\wrappers\vimeo\widgets;

use yii\base\InvalidConfigException;
use yii\helpers\Html;
use dlds\wrappers\vimeo\bundles\SelectboxAsset;

class Selectbox extends \yii\bootstrap\Widget {

    /**
     * @var Model the data model that this widget is associated with.
     */
    public $model;

    /**
     * @var string the model attribute that this widget is associated with.
     */
    public $attribute;

    /**
     * @var string the input name. This must be set if [[model]] and [[attribute]] are not set.
     */
    public $name;

    /**
     * @var string the input value.
     */
    public $value;

    /**
     * @var string placeholder string
     */
    public $placeholder = '';

    /**
     * @var int indicates minimum input length
     */
    public $minimumInputLength = 3;

    /**
     * @var string callback url
     */
    public $url;

    /**
     * @var boolean wheter to publish asset bundle
     */
    public $assets = true;

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();

        Html::addCssClass($this->options, 'form-control');

        if (!$this->hasModel() && $this->name === null)
        {
            throw new InvalidConfigException("Either 'name', or 'model' and 'attribute' properties must be specified.");
        }

        if (!isset($this->options['id']))
        {
            $this->options['id'] = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId();
        }

        if (!$this->url)
        {
            throw new InvalidConfigException('Url config parameter must be set.');
        }

        $this->registerJs();
    }

    /**
     * Executes the widget.
     */
    public function run()
    {
        if ($this->hasModel())
        {
            echo Html::activeTextInput($this->model, $this->attribute, $this->options);
        }
        else
        {
            echo Html::textInput($this->name, $this->value, $this->options);
        }

        if ($this->assets)
        {
            SelectboxAsset::register($this->view);
        }
    }

    /**
     * REgisteres JS
     */
    protected function registerJs()
    {
        $view = $this->getView();
        $view->registerJs("
            (function() {
                var CONFIG = {
                    placeholder: '" . $this->placeholder . "',
                    minimumInputLength: " . $this->minimumInputLength . ",
                    ajax: {
                        url: '" . $this->url . "', 
                        dataType: 'json',
                        quietMillis: 250,
                        data: function (term, page) {
                            return {
                                q: term,
                            };
                        },
                        results: function (data, page) {
                            return {results: data};
                        },
                        cache: true
                    },
                    initSelection: function (element, callback) {
                        var id = $(element).val();
                        if (id !== '') {
                            $.ajax('" . $this->url . "&id=' + id, {
                                dataType: 'json'
                            }).done(function (data) {
                                callback(data);
                            });
                        }
                    },
                    formatResult: formatResult,
                    formatSelection: formatSelection,
                    escapeMarkup: function (m) {
                        return m;
                    }
                }

                function formatResult(result) {
                    return result.name;
                }

                function formatSelection(result) {
                    return result.name;
                }
                
                $('#" . $this->id . "').select2(CONFIG);
            })();
        ", \yii\web\View::POS_READY);
    }

    /**
     * @return boolean whether this widget is associated with a data model.
     */
    protected function hasModel()
    {
        return $this->model instanceof \yii\base\Model && $this->attribute !== null;
    }

}
