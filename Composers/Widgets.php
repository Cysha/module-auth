<?php namespace Cms\Modules\Auth\Composers;

use Cms\Modules\Auth\Repositories\User\RepositoryInterface as UserRepo;

class Widgets
{
    /**
     * @var Cms\Modules\Auth\Repositories\User\RepositoryInterface
     */
    protected $user;

    public function __construct(UserRepo $user)
    {
        $this->user = $user;
    }

    public function UserCount($view)
    {
        $count = $this->user->all()->count();
        $view->with('counter', $count);
    }

    public function RegisteredTodayCount($view)
    {
        $count = $this->user
            ->where('created_at', sprintf('%d-%d-%d 00:00:00', date('Y'), date('m'), date('d')), '>=')
            ->where('created_at', sprintf('%d-%d-%d 23:59:59', date('Y'), date('m'), date('d')), '<')
            ->get()
            ->count();

        $view->with('counter', $count);
    }

    public function LatestUsers($view)
    {
        $users =  $this->user->transformModels(
            $this->user->orderBy('created_at', 'desc')->limit(8)->get()
        );
        $view->with('users', $users);
    }

}
