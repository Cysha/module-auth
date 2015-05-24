<?php namespace Cms\Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissionGroups = get_array_column(config('cms'), 'permissions');

        $seedModel = 'Cms\Modules\Auth\Models\Permission';
        foreach ($permissionGroups as $group) {
            foreach ($group as $type => $permissions) {
                foreach ($permissions as $perm) {
                    $permission = with(new $seedModel)->fill([
                        'type'              => 'privilege',
                        'action'            => $perm,
                        'resource_type'     => $type,
                        'resource_id'       => NULL,
                        'created_at'        => Carbon::now(),
                        'updated_at'        => Carbon::now(),
                    ]);
                    $save = $permission->save();

                    if ($save === false) {
                        print_r($permission->getErrors());
                        die();
                    }

                    with(new \Cms\Modules\Auth\Models\Role)->find(1)->permissions()->save($permission);
                    with(new \Cms\Modules\Auth\Models\Role)->find(2)->permissions()->save($permission);
                }

            }
        }
    }
}
