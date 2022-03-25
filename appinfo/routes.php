<?php

return [
    'routes' => [
        ['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
        [
            'name' => 'page#getUserInfo',
            'url' => '/user',
            'verb' => 'GET'
        ],
        [
            'name' => 'menu_api#preflighted_cors', 'url' => '/api/0.1/{path}',
            'verb' => 'OPTIONS', 'requirements' => ['path' => '.+']
        ],
        //////////  M E N U S  //////////
        [
            'name' => 'menu#index',
            'url' => '/menus',
            'verb' => 'GET',
        ],
        [
            'name' => 'menu#indexG',
            'url' => '/menusG',
            'verb' => 'GET',
        ],
        [
            'name' => 'menu#dashboard',
            'url' => '/menus/dashboard',
            'verb' => 'GET',
        ],
        [
            'name' => 'menu#create',
            'url' => '/menus',
            'verb' => 'POST',
        ],
        [
            'name' => 'menu#update',
            'url' => '/menus/{id}',
            'verb' => 'POST',
            'requirements' => ['id' => '\d+'],
        ],
        [
            'name' => 'menu#changeOrder',
            'url' => '/menus/order',
            'verb' => 'POST'
        ],
        [
            'name' => 'menu#destroy',
            'url' => '/menus/{id}',
            'verb' => 'DELETE',
            'requirements' => ['id' => '\d+'],
        ],

        //////////  N E W S  //////////
        [
            'name' => 'news#index',
            'url' => '/news/{id}',
            'verb' => 'POST'
        ],
        [
            'name' => 'news#indexG',
            'url' => '/newsG/{id}',
            'verb' => 'POST'
        ],
        [
            'name' => 'news#dashboard',
            'url' => '/news/dashboard',
            'verb' => 'GET',
        ],
        [
            'name' => 'news#create',
            'url' => '/news',
            'verb' => 'POST',
        ],
        [
            'name' => 'news#update',
            'url' => '/news/update/{id}',
            'verb' => 'POST',
            'requirements' => ['id' => '\d+'],
        ],
        [
            'name' => 'news#publication',
            'url' => '/news/pub/{id}',
            'verb' => 'POST',
            'requirements' => ['id' => '\d+'],
        ],
        [
            'name' => 'news#pinNews',
            'url' => '/news/pin/{id}',
            'verb' => 'POST',
            'requirements' => ['id' => '\d+']
        ],
        [
            'name' => 'news#getCategory',
            'url' => '/news/category',
            'verb' => 'GET',
        ],
        [
            'name' => 'news#destroy',
            'url' => '/news/{id}',
            'verb' => 'DELETE',
            'requirements' => ['id' => '\d+'],
        ],

        //////////  A P P S  //////////
        [
            'name' => 'apps#index',
            'url' => '/apps',
            'verb' => 'GET',
        ],
        [
            'name' => 'apps#indexG',
            'url' => '/appsG',
            'verb' => 'GET',
        ],
        [
            'name' => 'apps#dashboard',
            'url' => '/apps/dashboard',
            'verb' => 'GET',
        ],
        [
            'name' => 'apps#create',
            'url' => '/apps',
            'verb' => 'POST',
        ],
        [
            'name' => 'apps#update',
            'url' => '/apps/{id}',
            'verb' => 'POST',
            'requirements' => ['id' => '\d+'],
        ],
        [
            'name' => 'apps#destroy',
            'url' => '/apps/{id}',
            'verb' => 'DELETE',
            'requirements' => ['id' => '\d+'],
        ],

        //////////  A P I  //////////
        [
            'name' => 'page#searchGroups',
            'url' => '/api/groups',
            'verb' => 'GET',
        ],
    ],
];
