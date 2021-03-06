<?php

namespace yiichina\mdeditor;

use yii\Helpers\Url;
use yii\Helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * This is just an example.
 */
class MdEditor extends \yii\widgets\InputWidget
{
    public $debug = false;
    public $codeMirror;
    public $buttons;
    public $disabledButtons;
    public $allowUpload = false;

    public function init()
    {
        parent::init();
        $view = $this->getView();
        MdEditorAsset::register($view);
        $optionsArray = [];
        if($this->debug) {
            $optionsArray['debug'] = true;
        }
        if(!empty($this->codeMirror)) {
            $optionsArray['codeMirror'] = $this->codeMirror;
        }
        if($this->allowUpload) {
            FileUploadAsset::register($view);
            $optionsArray['buttons']['link']['action'] = new JsExpression("function() { $.setUploader(this, 'file', '" . Url::to(['/attachment/file']) . "'); }");
            $optionsArray['buttons']['image']['action'] = new JsExpression("function() { $.setUploader(this, 'image', '" . Url::to(['/attachment/image']) . "'); }");
        }
        if(!empty($this->buttons)) {
            $optionsArray['buttons'] = $this->buttons;
        }
        if(!empty($this->codeMirror)) {
            $optionsArray['disabledButtons'] = $this->disabledButtons;
        }
        $options = Json::encode($optionsArray);
        $view->registerJs("$(\"#{$this->options['id']}\").mdEditor($options)");
    }

    public function run()
    {
        if($this->hasModel()) {
            return Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {
            return Html::textarea($this->name, $this->value, $this->options);
        }
    }
}
