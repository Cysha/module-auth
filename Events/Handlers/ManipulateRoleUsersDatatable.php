<?php namespace Cms\Modules\Auth\Events\Handlers;

use BeatSwitch\Lock\Manager;
use Cms\Modules\Admin\Events\GotDatatableConfig;
use Cms\Modules\Auth\Models\Role;
use Illuminate\Support\Facades\Request;

class ManipulateRoleUsersDatatable
{
    protected $manager;

    public function __construct(Manager $lockManager)
    {
        $this->manager = $lockManager;
    }

    /**
     * Handle the event.
     *
     * @param  GotDatatableConfig $event
     * @return void
     */
    public function handle(GotDatatableConfig $event)
    {
        if (Request::route()->getName() !== 'admin.role.users') {
            return;
        }

        // grab the role
        $role_id = Request::segment(3);
        $role = with(new Role)->find($role_id);

        // reset the title
        $title = 'Role: '.e($role->name);
        array_set($event->config, 'page.title', $title);

        array_set($event->config, 'page.alert', [
            'class' => 'info',
            'text'  => '<i class="fa fa-info-circle"></i> This panel will show you all the users attached to this role.'
        ]);

        // clear a few options out
        array_set($event->config, 'options.source', null);
        array_set($event->config, 'options.column_search', false);

        // rejig the columns
        array_set($event->config, 'columns.actions', null);
        array_set($event->config, 'columns.roles', null);

        // rebuild the collection
        array_set($event->config, 'options.collection', function () use ($role_id) {

            $authModel = config('auth.model');
            return with(new $authModel)
                ->whereHas('roles', function ($query) use ($role_id) {
                    $query->where('role_id', $role_id);
                })
                ->get();
        });


        return $event->config;
    }

}
