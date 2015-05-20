<?php namespace Cms\Modules\Auth\Http\Controllers\Backend;

use Cms\Modules\Core\Http\Controllers\BaseAdminController;
use Cms\Modules\Admin\Traits\DataTableTrait;
use File;

class SocialiteManagerController extends BaseAdminController
{
    public function getIndex()
    {
        $this->theme->setTitle('<i class="fa fa-share-alt-square"></i> Socialite Manager');
        $this->theme->breadcrumb()->add('Socialite Manager', route('admin.config.socialite'));

        return $this->setView('admin.config.keys.socialite', [
            'socialiteProviders' => $this->getProviders(),
            'installedProviders' => $this->getInstalledProviders(),
        ]);
    }

    private function getProviders()
    {
        return config('cms.auth.socialite');
    }

    private function getInstalledProviders()
    {
        $path = base_path('vendor/socialiteproviders/');

        $socialiteProviders = ['facebook', 'twitter', 'google', 'github'];
        if (File::exists($path)) {
            foreach (File::Directories($path) as $dir) {
                $dir = class_basename($dir);
                if ($dir == 'manager') { continue; }

                $socialiteProviders[] = $dir;
            }
        }

        return $socialiteProviders;
    }

}
