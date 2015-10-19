<?php

namespace uploadcare\yii2;

use Uploadcare\File;
use yii\helpers\Html;
use Yii;

/**
 * Class Image
 * @package uploadcare\yii2
 */
class Image extends File
{
    private $_options;

    public function __construct($uuid, $options = [])
    {
        parent::__construct($uuid, Yii::$app->uploadcare->uploadcareApi);
        $this->_options = $options;

    }

    /**
     * @param $uuid
     * @param $options
     * @return Image
     */
    public static function img($uuid, $options)
    {
        return new Image($uuid, $options);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return Html::img($this->getUrl(), $this->_options);
    }
}
