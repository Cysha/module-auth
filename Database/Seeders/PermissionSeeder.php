<?php namespace Cms\Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissionGroups = config('cms.auth.permissions');

        $seedModel = 'Cms\Modules\Auth\Models\Permission';
        foreach ($permissionGroups as $type => $permissions) {

            foreach($permissions as $perm) {
                $permission = with(new $seedModel)->create([
                    'type'              => 'privilege',
                    'action'            => $perm,
                    'resource_type'     => $type,
                    'resource_id'       => NULL,
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now(),
                ]);

                with(new \Cms\Modules\Auth\Models\Role)->find(1)->permissions()->save($permission);
                with(new \Cms\Modules\Auth\Models\Role)->find(2)->permissions()->save($permission);
            }

        }
    }
}
