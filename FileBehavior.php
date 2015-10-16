<?php

namespace sokrat\uploadcare;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Class FileBehavior
 * Save file and set UUID as attribute value
 * @property ActiveRecord $owner
 * @package sokrat\uploadcare
 */
class FileBehavior extends Behavior
{
    /**
     * file attributes
     * @var array
     */
    public $attributes = [];


    public $events = [
        ActiveRecord::EVENT_BEFORE_VALIDATE => 'evaluate',
        ActiveRecord::EVENT_AFTER_DELETE => 'delete'
    ];

    /**
     * Delete files when they have been replaced
     * @var bool
     */
    public $deleteUnused = true;

    /**
     * @inheritdoc
     */
    public function events()
    {
        return $this->events;
    }


    /**
     * Evaluates the attribute value and assigns it to the current attributes.
     * @param \yii\base\Event $event
     */
    public function evaluate($event)
    {
        foreach ($this->attributes as $attribute) {
            // ignore attribute names which are not string (e.g. when set by TimestampBehavior::updatedAtAttribute)
            if (is_string($attribute) && $this->isAttributeChanged($attribute)) {
                if ($this->deleteUnused) {
                    $this->deleteValue($this->owner->getOldAttribute($attribute));
                }
                $this->owner->$attribute = $this->getValue($attribute);
            }
        }
    }

    protected function compareUUID($oldValue, $newValue)
    {
        /** @var \UploadCare\File $oldFile */
        $oldFile = Yii::$app->uploadcare->getFile($oldValue);
        /** @var \UploadCare\File $newFile */
        $newFile = Yii::$app->uploadcare->getFile($newValue);

        if ($oldFile->getUuid() == $newFile->getUuid()) {
            return false;
        } else {
            return true;
        }
    }

    protected function isAttributeChanged($attribute)
    {
        $oldValue = $this->owner->getOldAttribute($attribute);
        $newValue = $this->owner->$attribute;

        if (!empty($oldValue) && !empty($newValue)) {
            return $this->compareUUID($oldValue, $newValue);
        } elseif (!empty($oldValue) || !empty($newValue)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Deletes uploaded files
     * @param $event
     */
    public function delete($event)
    {
        foreach ($this->attributes as $attribute) {
            // ignore attribute names which are not string (e.g. when set by TimestampBehavior::updatedAtAttribute)
            if (is_string($attribute)) {
                $this->deleteValue($this->owner->$attribute);
            }
        }
    }

    /**
     * Return new UUID for attribute value
     * @param $attribute
     * @return string
     */
    public function getValue($attribute)
    {
        if (empty($this->owner->$attribute)) {
            return null;
        }
        /** @var \UploadCare\File $file */
        $file = Yii::$app->uploadcare->getFile($this->owner->$attribute);
        $file->store();
        return $file->getUuid();
    }

    /**
     * deletes file on uploadcare
     * @param $value
     */
    protected function deleteValue($value)
    {
        if (!empty($value)) {
            /** @var \UploadCare\File $file */
            $file = Yii::$app->uploadcare->getFile($value);
            $file->delete();
        }
    }
}
