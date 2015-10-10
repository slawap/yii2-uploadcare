Yii2 wrapper for Uploadcare PHP package
=======================================
>Uploadcare handles uploading, storing and processing files for you. All components of Uploadcare, from widget to CDN, work seamlessly together, require almost no configuration and allow maximum flexibility exactly when you need it.
[PHP Library](https://github.com/uploadcare/uploadcare-php)
[Uploadcare documentation](https://uploadcare.com/documentation/)

Configuration
=========
Api is a yii2 component, which is a facade to \Uploadcare\Api.

Example config:
```php
'components' => [
    'uploadcare' => [
        'class' => 'sokrat\uploadcare\Api',
        'publicKey' => 'your_public_key',
        'secretKey' => 'your_secret_key',
        'globalWidgetOptions' => [
            'UPLOADCARE_LOCALE' => 'ru',
        ]
    ]
]
```

globalWidgetOptions - allow you set global options for all call of widget. [supported options](https://uploadcare.com/documentation/widget/)

Widget
======
Show file(s) input widget

Example call:
```php

use sokrat\uploadcare\UploadCare;

echo $form->field($model, 'logo')->widget(
    UploadCare::className(),
    [
        'options' => [
            'data' => [
                'multiple' => true
            ]
        ],
        'validators' => [
            new \yii\web\JsExpression('function (fileInfo) {
              console.log("test1");
            }'),
            new \yii\web\JsExpression('function (fileInfo) {
              console.log("test2");
            }')
        ]
    ]
);
```

Local widget settings you can set using html5 data attributes. [supported options](https://uploadcare.com/documentation/widget/)

[Validators allow restricting user choice to certain kind of files.](https://uploadcare.com/documentation/javascript_api/#validation)

Store file(s) permanently and set UUID to model attribute
=========================================================
example config:
```php
    public function behaviors()
    {
        return [
            'logo' => [
                'class' => FilesBehavior::class,
                'groupUUID' => false,
                'attributes' => [
                    self::EVENT_AFTER_VALIDATE => ['logo']
                ]
            ]
        ];
    }
```
File(s)Behavior call api method for save file(s) permanently.

FilesBehavior - set UIID to model attribute

FilesBehavior - group UIID or files UIID array (depending on the groupUUID attribute) to model attribute.

Show files
==========

