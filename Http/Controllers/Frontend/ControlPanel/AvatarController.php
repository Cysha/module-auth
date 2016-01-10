<?php namespace Cms\Modules\Auth\Http\Controllers\Frontend\ControlPanel;

use Cms\Modules\Auth\Http\Requests\FrontendAvatarRequest;
use Cms\Modules\Auth\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AvatarController extends BaseController
{

    public function getForm()
    {
        $data = $this->getUserDetails();
        $this->theme->breadcrumb()->add('Avatar Settings', route('pxcms.user.avatar'));

        return $this->setView('controlpanel.avatars', $data);
    }

    public function postForm(FrontendAvatarRequest $input) {
        $fields = $input->only(['avatar']);

        $user = Auth::user();

        $user->hydrateFromInput($fields);

        if ($user->save() === false) {
            return redirect()->back()->withError('Error: Avatar cannot be saved, please try again.');
        }

        return redirect()->back()->withInfo('Avatar Saved Successfully.');
    }
}
