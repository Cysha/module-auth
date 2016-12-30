<?php

use Dingo\Api\Routing\Router as ApiRouter;

$router->version('v1', ['middleware' => ['api.auth'], 'namespace' => 'V1'], function (ApiRouter $router) {
    $router->get('user', ['uses' => 'PagesController@getUser']);

    $router->group(['prefix' => 'widget/auth'], function (ApiRouter $router) {
        $router->get('user-count', 'WidgetController@getUserCount');
        $router->get('daily-user-count', 'WidgetController@getDailyUserCount');
        $router->post('recent-user-list', 'WidgetController@getRecentUserList');
        $router->get('daily-register-count', 'WidgetController@getDailyRegisterCounts');
    });

});
