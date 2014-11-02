<?php
return [
    'name' => 'My application',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        // you can set your theme here (template comes with 'default' and 'cool')
        'view' => [
            'theme' => [
                'pathMap' => ['@app/views' => '@webroot/themes/cool'],
                'baseUrl' => '@web/themes/cool',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        // if you decide to use bootstrap and jquery from CDN, uncomment assetManager settings
        // 'assetManager' => [
        //     'bundles' => [
        //         // use bootstrap css from CDN
        //         'yii\bootstrap\BootstrapAsset' => [
        //             'sourcePath' => null,   // do not use file from our server
        //             'css' => [
        //                 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css']
        //         ],
        //         // use bootstrap js from CDN
        //         'yii\bootstrap\BootstrapPluginAsset' => [
        //             'sourcePath' => null,   // do not use file from our server
        //             'js' => [
        //                 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js']
        //         ],
        //         // use jquery from CDN
        //         'yii\web\JqueryAsset' => [
        //             'sourcePath' => null,   // do not publish the bundle
        //             'js' => [
        //                 '//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js',
        //             ]
        //         ],
        //     ],
        // ],
    ], // components
];
