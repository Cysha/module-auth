<?php namespace Cms\Modules\Auth\Http\Controllers\Frontend\ControlPanel;

use Cms\Modules\Auth\Http\Requests\FrontendSecurityRequest;
use Cms\Modules\Auth\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SecurityController extends BaseController
{

    public function getForm()
    {
        $data = $this->getUserDetails();
        $this->theme->breadcrumb()->add('Security Settings', route('pxcms.user.security'));

        return $this->setView('controlpanel.security', $data);
    }

    /**
     * TODO: check out https://github.com/Cysha/pxcms-auth/issues/4 for augmenting this...
     */
    public function updatePassword(FrontendSecurityRequest $input)
    {
        $newPass = $input->get('new_password', null);
        $newPassConfirm = $input->get('new_password_confirmation', null);

        // check the new passwords match first
        if (md5($newPass) !== md5($newPassConfirm)) {
            return redirect()->back()->withErrors([
                'new_password' => 'Passwords do not match',
                'new_password_confirmation' => 'Passwords do not match',
            ]);
        }

        // grab the old password
        $oldPass = $input->get('old_password', null);

        // grab the current user
        $user = Auth::user();

        // make sure its valid against current users password
        if (!Hash::check($oldPass, $user->password)) {
            return redirect()->back()->withErrors([
                'old_password' => 'Old password doesnt match one on file.'
            ]);
        }

        // if all checks out, change the users password to the new one
        // password auto gets run through bcrypt() via the model attributes
        $user->hydrateFromInput(['password' => $newPass]);

        // make sure we can save
        if ($user->save() === false) {
            return redirect()->back()->withErrors($user->getErrors());
        }

        // TODO: check to see if we need to fire off an event notifying user about this change
        // if () {
        //    event(new UserPasswordWasChanged($user));
        // }

        // redirect back!
        return redirect()->back()->withInfo('Password Updated');
    }

}
