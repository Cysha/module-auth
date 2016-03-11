<?php

namespace Cms\Modules\Auth\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class ModulePublishPermissionsCommand extends Command
{
    protected $name = 'module:publish-permissions';
    protected $readableName = 'Publish Module Permissions';
    protected $description = 'Publish any new permissions a module might have.';

    public function fire()
    {
        // publish the modules config files to make sure they are upto date
        $this->info('Making sure all the config files are up to date.');
        $this->callSilent('module:publish-config', ['--force' => null]);

        $permissionGroups = get_array_column(config('cms'), 'permissions');
        $seedModel = 'Cms\Modules\Auth\Models\Permission';

        $this->comment('Processing Permissions...');

        // loop over the permissions
        foreach ($permissionGroups as $group) {
            foreach ($group as $type => $permissions) {
                foreach ($permissions as $perm => $readableName) {

                    // see if the permission is in there already
                    $permission = with(new $seedModel())
                        ->whereType('privilege')
                        ->whereAction($perm)
                        ->whereResourceType($type)
                        ->first();

                    // if not then throw it in
                    if ($permission === null) {
                        $permission = with(new $seedModel())
                            ->fill([
                                'type' => 'privilege',
                                'action' => $perm,
                                'resource_type' => $type,
                                'readable_name' => $readableName,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ])
                            ->save();

                        $this->info('Permission added: '.$perm.'@'.$type);
                    }
                }
            }
        }
        $this->info('Done...');
    }

    protected function getOptions()
    {
        return [
        ];
    }
}
