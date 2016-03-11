<?php

namespace Cms\Modules\Auth\Http\Controllers\Frontend\ControlPanel;

use Cms\Modules\Auth\Http\Requests\FrontendSettingsRequest;
use Illuminate\Support\Facades\Auth;

class SettingsController extends BaseController
{
    public function getForm()
    {
        $data = $this->getUserDetails();
        $this->theme->breadcrumb()->add('Account Settings', route('pxcms.user.settings'));

        return $this->setView('controlpanel.settings', $data);
    }

    public function postForm(FrontendSettingsRequest $input)
    {
        $fields = $input->only(['username', 'name', 'use_nick', 'email']);

        $user = Auth::user();

        $user->hydrateFromInput($fields);

        if ($user->save() === false) {
            return redirect()->back()->withError('Error: Settings cannot be saved, please try again.');
        }

        return redirect()->back()->withInfo('Settings Saved Successfully.');
    }
}
