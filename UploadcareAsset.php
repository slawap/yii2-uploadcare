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
    public $sourcePath = '@bower/uploadcare';
    public $js = [
        'uploadcare.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}