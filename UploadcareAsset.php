<?php
/**
 * Class UploadcareAsset Asset bundle for
 * @package uploadcare\yii2
 * @author Vyacheslav Panin
 * @since 0.2.0
 */

namespace uploadcare\yii2;

use yii\web\AssetBundle;

class UploadcareAsset extends AssetBundle
{
    const CDN_SOURCE_PATH = '//ucarecdn.com/widget/%s/uploadcare/uploadcare.min.js';

    public $version = false;

    public $sourcePath = '@bower/uploadcare';
    public $js = [
        'uploadcare.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function registerAssetFiles($view)
    {
        if ($this->version) {
            $version = is_string($this->version) ? $this->version : UploadCare::VERSION;
            $this->baseUrl = sprintf(self::CDN_SOURCE_PATH, $version);
        }
        parent::registerAssetFiles($view);
    }
}