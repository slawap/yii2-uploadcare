<?php

namespace uploadcare\yii2;

use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;
use Yii;

/**
 * Class UploadCare
 * @property string $globalVariables @see $this->getGlobalVariables()
 * @package backend\widgets
 */
class UploadCare extends InputWidget
{
    /**
     * @var array
     */
    public $validators = [];

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!Yii::$app->has('uploadcare')) {
            throw new InvalidConfigException('You should configure uploadcare component');
        }

        if (empty($this->value)) {
            if (isset($this->options['value'])) {
                $this->value = $this->options['value'];
            } elseif ($this->hasModel()) {
                $this->value = $this->model->{$this->attribute};
            }
            $this->options['value'] = false;
        }

        if (empty($this->options['id'])) {
            $this->options['id'] = $this->id;
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeHiddenInput($this->model, $this->attribute, $this->options);
        } else {
            echo Html::hiddenInput($this->name, $this->value, $this->options);
        }
        $this->registerClientScript();
    }

    protected function registerClientScript()
    {
        $view = $this->getView();

        UploadcareAsset::register($view);

        $view->registerJs($this->globalVariables, $view::POS_HEAD, 'uploadcare');

        $validators = Json::encode($this->validators);
        $value = Json::encode($this->value);

        $view->registerJs("
            var widget = uploadcare.Widget('#{$this->options['id']}');
            widget.value({$value});
            {$validators}.forEach(function(value){
                widget.validators.push(value)
            })
        ");
    }

    protected function getGlobalVariables()
    {
        $js[] = sprintf("UPLOADCARE_PUBLIC_KEY='%s'", Yii::$app->uploadcare->publicKey);

        foreach (Yii::$app->uploadcare->globalWidgetOptions as $constant => $value) {
            $js[] = "{$constant}='$value'";
        }
        return implode(';', $js);
    }
}
