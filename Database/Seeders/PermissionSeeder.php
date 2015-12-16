<?php

namespace Cms\Modules\Auth\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissionGroups = get_array_column(config('cms'), 'permissions');

        $seedModel = 'Cms\Modules\Auth\Models\Permission';
        foreach ($permissionGroups as $group) {
            foreach ($group as $type => $permissions) {
                foreach ($permissions as $action => $name) {
                    $permission = with(new $seedModel())->fill([
                        'type'              => 'privilege',
                        'action'            => $action,
                        'readable_name'     => $name,
                        'resource_type'     => $type,
                        'resource_id'       => null,
                        'created_at'        => Carbon::now(),
                        'updated_at'        => Carbon::now(),
                    ]);
                    $save = $permission->save();

                    if ($save === false) {
                        print_r($permission->getErrors());
                        die();
                    }

                    with(new \Cms\Modules\Auth\Models\Role())->find(1)->permissions()->save($permission);
                    with(new \Cms\Modules\Auth\Models\Role())->find(2)->permissions()->save($permission);
                }
            }
        }
    }
}
