<?php namespace Cms\Modules\Auth\Http\Controllers\Frontend\ControlPanel;

use Cms\Modules\Auth\Http\Requests\FrontendAvatarRequest;
use Cms\Modules\Auth\Http\Requests\FrontendAvatarUploadRequest;
use Cms\Modules\Auth\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AvatarController extends BaseController
{

    public function getForm()
    {
        $data = $this->getUserDetails();
        $this->theme->breadcrumb()->add('Avatar Settings', route('pxcms.user.avatar'));
        $this->theme->asset()->add('auth_dropzone_js', 'https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/dropzone.js', ['theme']);
        $this->theme->asset()->add('auth_dropzone_css', 'https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/basic.css', ['theme']);

        return $this->setView('controlpanel.avatars', $data);
    }

    public function postForm(FrontendAvatarRequest $input) {
        $fields = $input->only(['avatar']);

        $user = Auth::user();

        $user->hydrateFromInput($fields);

        if ($user->save() === false) {
            return redirect()->back()
                ->withError('Error: Avatar choice cannot be saved, please try again.');
        }

        return redirect()->back()
            ->withInfo('Avatar Choice Saved Successfully.');
    }

    public function uploadAvatar(FrontendAvatarUploadRequest $input) {
        $user = Auth::user();

        // grab the file
        $file = $input->file('file');
        $extension = $file->getClientOriginalExtension();

        // rename it a little
        $filename = sha1(time().time()).'.'.$extension;

        // move it into the upload directory
        $upload_success = $file->move(public_path($user->uploadDir), $filename);

        // if successfull, add url to users avatar
        if ($upload_success) {
            $user->avatar = $user->uploadDir .'/'. $filename;
            $user->save();

            return response()->json('success', 200);
        } else {
            return response()->json('error', 400);
        }
    }
}
