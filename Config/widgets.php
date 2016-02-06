<?php

return [
    'dashboard' => [
        [
            'view'  => 'auth::widgets.userCount',
            'name'  => 'User Count',
            'class' => '\Cms\Modules\Auth\Composers\Widgets@UserCount',
        ],
        [
            'view'  => 'auth::widgets.latestUsers',
            'name'  => 'Latest Registered Users',
            'class' => '\Cms\Modules\Auth\Composers\Widgets@LatestUsers',
        ],
        [
            'view'  => 'auth::widgets.registeredTodayCount',
            'name'  => 'Users Registered Today',
            'class' => '\Cms\Modules\Auth\Composers\Widgets@RegisteredTodayCount',
        ],
    ],

];
