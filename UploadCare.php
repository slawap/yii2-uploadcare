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
     * Choose UploadCare version from CDN
     * true - use current version from CDN
     * '2.10.3' - use custom version
     *
     * @var bool | string
     */
    public static $useCDN = false;

    /**
     * Current UploadCare widget version
     */
    const VERSION = '2.10.4';

    /**
     * UploadCare widget
     * @var Api
     */
    private $_component;

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
        } else {
            $this->_component = Yii::$app->uploadcare;
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
        $view->registerJsFile($this->_component->widget->getScriptSrc());
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

        foreach ($this->_component->globalWidgetOptions as $constant => $value) {
            $js[] = "{$constant}='$value'";
        }
        return implode(';', $js);
    }
}
