<?php

namespace sokrat\uploadcare;

use Yii;

/**
 * Class FilesBehavior
 * Save file group and set group UUID or files UUID array as attribute value
 * @package sokrat\uploadcare
 */
class FilesBehavior extends FileBehavior
{
    /**
     * If true set group UUID to attribute value, otherwise set files UUID array
     * @var bool
     */
    public $groupUUID = true;

    private function getGroupFileUUIDs($groupUUID)
    {
        /** @var \Uploadcare\Group $group */
        $group = Yii::$app->uploadcare->getGroup($groupUUID);
        $files = [];
        foreach ($group->getFiles() as $file) {
            /** @var \Uploadcare\File $file */
            $files[] = $file->getUuid();
        }
        return $files;
    }


    protected function compareUUID($oldValue, $newValue)
    {
        if (is_array($oldValue)) {
            $oldFiles = $oldValue;
        } else {
            $oldFiles = $this->getGroupFileUUIDs($oldValue);
        }

        $newFiles = $this->getGroupFileUUIDs($newValue);

        if (empty(array_diff($newFiles, $oldFiles))) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * @param $attribute
     * @return array|string
     */
    public function getValue($attribute)
    {
        if (empty($this->owner->$attribute)) {
            return $this->groupUUID ? null : [];
        }
        /** @var \Uploadcare\Group $group */
        $group = Yii::$app->uploadcare->getGroup($this->owner->$attribute);
        $group->store();
        if ($this->groupUUID) {
            return $group->getUuid();
        } else {
            $files = [];
            foreach ($group->getFiles() as $file) {
                /** @var \UploadCare\File $file */
                $files[] = $file->getUuid();
            }
            return $files;
        }
    }

    /**
     * deletes file on uploadcare
     * @param $value
     */
    protected function deleteValue($value)
    {
        if (!empty($value)) {
            if (!is_array($value)) {
                /** @var \Uploadcare\Group $group */
                $group = Yii::$app->uploadcare->getGroup($value);
                $files = $group->getFiles();
            } else {
                $files = [];
                foreach ($value as $current) {
                    $files[] = Yii::$app->uploadcare->getFile($current);
                }
            }

            foreach ($files as $file) {
                /** @var \UploadCare\File $file */
                $file->delete();
            }
        }
    }
}
