<?php

namespace sokrat\uploadcare;

use Uploadcare\File;
use yii\helpers\Html;

/**
 * Class Image
 * @package sokrat\uploadcare
 */
class Image extends File
{
    private $_options;

    public function __construct($uuid, $options = [])
    {
        parent::__construct($uuid, \Yii::$app->uploadcare->api);
        $this->_options = $options;

    }

    public static function img($src, $options)
    {
        return new Image($src, $options);
    }

    public function __toString()
    {
        return Html::img($this->getUrl(), $this->_options);
    }
} 