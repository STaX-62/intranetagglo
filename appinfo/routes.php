<?php

return [
    'resources' => [
        // 'menu' => ['url' => '/menus'],
        // 'menu_api' => ['url' => '/api/0.1/menus']
    ],
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
            'name' => 'news#search',
            'url' => '/news/{id}/{search}',
            'verb' => 'GET',
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
            'url' => '/news/pub/{id}',
            'verb' => 'POST',
            'requirements' => ['id' => '\d+'],
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
    ],
];
