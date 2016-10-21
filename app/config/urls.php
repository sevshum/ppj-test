<?php

return [
    'class' => 'app\components\I18nUrlManager',
    'rules' => [
        '/' => '/site/index',
        '/page/<slug:[\w\-]+>' => '/page/pages/show',
        '/user/<_a:\[\w\-]+>' => '/user/users/<_a>',
        '/payments/<_a:[\w\-]+>' => '/payment/payments/<_a>',
        '<_m:\w+>/<_c:[\w\-]+>/<id:\d+>' => '<_m>/<_c>/view',
        '<_m:\w+>/<_c:[\w\-]+>/<_a:[\w\-]+>/<id:\d+>' => '<_m>/<_c>/<_a>',
        '<_m:\w+>/<_c:[\w\-]+>/<_a:[\w\-]+>' => '<_m>/<_c>/<_a>',

        '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
        '<_c:[\w\-]+>/<_a:[\w\-]+>/<id:\d+>' => '<_c>/<_a>',
        '<_c:[\w\-]+>/<_a:[\w\-]+>' => '<_c>/<_a>',
    ]
];

