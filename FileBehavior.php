<?php

namespace sokrat\uploadcare;

use yii\base\Behavior;
use Yii;

/**
 * Class FileBehavior
 * Save file and set UUID as attribute value
 * @package sokrat\uploadcare
 */
class FileBehavior extends Behavior
{
    /**
     * @var array list of attributes that are to be automatically filled with the value specified via [[value]].
     * The array keys are the ActiveRecord events upon which the attributes are to be updated,
     * and the array values are the corresponding attribute(s) to be updated. You can use a string to represent
     * a single attribute, or an array to represent a list of attributes. For example,
     *
     * ```php
     * [
     *     ActiveRecord::EVENT_BEFORE_INSERT => ['attribute1', 'attribute2'],
     *     ActiveRecord::EVENT_BEFORE_UPDATE => 'attribute2',
     * ]
     * ```
     */
    public $attributes = [];

    /**
     * @inheritdoc
     */
    public function events()
    {
        return array_fill_keys(array_keys($this->attributes), 'evaluateAttributes');
    }


    /**
     * Evaluates the attribute value and assigns it to the current attributes.
     * @param \yii\base\Event $event
     */
    public function evaluateAttributes($event)
    {
        if (!empty($this->attributes[$event->name])) {
            $attributes = (array) $this->attributes[$event->name];
            foreach ($attributes as $attribute) {
                // ignore attribute names which are not string (e.g. when set by TimestampBehavior::updatedAtAttribute)
                if (is_string($attribute)) {
                    $this->owner->$attribute = $this->getValue($attribute);
                }
            }
        }
    }

    /**
     * @param $attribute
     * @return string
     */
    public function getValue($attribute)
    {
        /** @var \UploadCare\File $file */
        $file = Yii::$app->uploadcare->getFile($this->owner->$attribute);
        $file->store();
        return $file->getUuid();
    }
}
