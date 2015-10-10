<?php

namespace sokrat\uploadcare;

use yii\base\Component;

/**
 * Facade for UploadCare\Api
 * Class UploadCare
 * @package backend\components
 */
class Api extends Component
{
    /**
     * @var \Uploadcare\Api
     */
    private $_api;

    public $publicKey;
    public $secretKey;
    public $apiHost;
    public $cdnHost;
    public $cdnProtocol;
    public $userAgent = 'Yii2 Uploadcare component';

    public $globalWidgetOptions = [];

    public function init()
    {
        $this->_api = new \Uploadcare\Api($this->publicKey, $this->secretKey, $this->userAgent, $this->cdnHost, $this->cdnProtocol);
    }

    public function __isset($name)
    {
        if (isset($this->_api->$name)) {
            return true;
        } else {
            return parent::__isset($name);
        }
    }

    public function __get($name)
    {
        if (property_exists($this->_api, $name)) {
            return $this->_api->$name;
        } else {
            return parent::__get($name);
        }
    }

    public function __call($name, $params)
    {
        if (method_exists($this->_api, $name)) {
            return call_user_func_array([$this->_api, $name], $params);
        } else {
            return parent::__call($name, $params);
        }
    }
}
