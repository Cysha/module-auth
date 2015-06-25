<?php namespace Cms\Modules\Auth\Events\Handlers;

use Cms\Modules\Admin\Events\GotDatatableConfig;
use Illuminate\Support\Facades\Request;
use BeatSwitch\Lock\Manager;

class ManipulateUserApiKeyDatatable
{
    /**
     * Handle the event.
     *
     * @param  GotDatatableConfig $event
     * @return void
     */
    public function handle(GotDatatableConfig $event)
    {
        if (Request::url() !== route('admin.user.apikey', Request::segment(3))) {
            return;
        }

        // grab the user
        $authModel = config('auth.model');
        $user = with(new $authModel)->find(Request::segment(3));

        // reset the title
        $title = 'User: '.e($user->screenname);
        array_set($event->config, 'page.title', $title);

        array_set($event->config, 'page.alert', [
            'class' => 'info',
            'text'  => '<i class="fa fa-info-circle"></i> This panel will show you all the API keys this user has.'
        ]);


        // clear a few options out
        array_set($event->config, 'options.source', null);

        // rebuild the collection
        array_set($event->config, 'options.collection', function () use ($user) {
            $model = 'Cms\Modules\Auth\Models\ApiKey';
            return $model::where('user_id', $user->id)->get();
        });

        // rejig the columns
        array_set($event->config, 'columns.actions', null);
        array_set($event->config, 'columns.user', null);

        return $event->config;
    }

}
