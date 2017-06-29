<?php
/**
 * Class UploadcareAsset Asset bundle for
 * @package uploadcare\yii2
 * @author Vyacheslav Panin
 * @since 0.2.0
 */
namespace uploadcare\yii2;

use yii\base\InvalidConfigException;
use yii\web\AssetBundle;
use yii;

class UploadcareAsset extends AssetBundle
{
    public $sourcePath = null; //use CDN only

    public $depends = [
        'yii\web\JqueryAsset',
    ];


    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!Yii::$app->has('uploadcare')) {
            throw new InvalidConfigException('You should configure uploadcare component');
        }

        $this->js = [Yii::$app->uploadcare->widget->getScriptSrc(null, false)];

        parent::init();
    }
}