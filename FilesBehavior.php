<?php

namespace uploadcare\yii2;

use Yii;

/**
 * Class FilesBehavior
 * Save file group and set group UUID or files UUID array as attribute value
 * @package uploadcare\yii2
 */
class FilesBehavior extends FileBehavior
{
    /**
     * If true set group UUID to attribute value, otherwise set files UUID array
     * @var bool
     */
    public $groupUUID = true;


    /**
     * @param $attribute
     * @return array|string
     */
    public function getValue($attribute)
    {
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
}
