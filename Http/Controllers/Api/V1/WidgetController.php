<?php

namespace Cms\Modules\Auth\Http\Controllers\Api\V1;

use Cms\Modules\Auth\Repositories\User\RepositoryInterface as UserRepo;
use Cms\Modules\Core\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;

class WidgetController extends BaseApiController
{
    public function getUserCount(UserRepo $userRepo)
    {
        return $this->sendResponse('ok', 200, [
            'user_count' => $userRepo->count(),
        ]);
    }

    public function getDailyUserCount(UserRepo $userRepo)
    {
        $count = $userRepo
            ->where('created_at', sprintf('%d-%02d-%02d 00:00:00', date('Y'), date('m'), date('d')), '>=')
            ->where('created_at', sprintf('%d-%02d-%02d 23:59:59', date('Y'), date('m'), date('d')), '<')
            ->count();

        return $this->sendResponse('ok', 200, [
            'user_count' => $count,
        ]);
    }

    public function getRecentUserList(UserRepo $userRepo, Request $request)
    {
        $userCount = $request->get('users', 8);
        if ($userCount <= 0) {
            $userCount = 8;
        }

        $users = $userRepo->orderBy('created_at', 'desc')->limit($userCount)->get();
        $users = $users->transform(function ($model) {
            return $model->transform();
        });

        return $this->sendResponse('ok', 200, [
            'users' => $users,
        ]);
    }

    public function getDailyRegisterCounts()
    {
        /*
        {
          labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
          datasets: [
            {
              label: 'GitHub Commits',
              backgroundColor: '#f87979',
              data: [40, 20, 12, 39, 10, 40, 39, 80, 40, 20, 12, 11]
            }
          ]
        }

        */
        return [
            'labels' => [
                '2016-12-01',
                '2016-12-02',
                '2016-12-03',
                '2016-12-04',
                '2016-12-05',
                '2016-12-06',
                '2016-12-07',
                '2016-12-08',
                '2016-12-09',
                '2016-12-10',
            ],
            'datasets' => [
                [
                    'label' => 'Registered Users',
                    'backgroundColor' => '#ccc',
                    'data' => [2, 7, 5, 4, 10, 7, 5, 3, 1, 15],
                ],
            ],
        ];
    }
}
