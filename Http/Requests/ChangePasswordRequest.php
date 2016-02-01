<?php namespace Cms\Modules\Auth\Http\Requests;

use Cms\Http\Requests\Request;
use Auth;

class ChangePasswordRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'old_password' => 'required_with:new_password',
            'new_password' => 'required_with:old_password|confirmed|min:8',
            'new_password_confirmation' => 'required_with:old_password,new_password',
        ];

        // if we wanted secure passwords...
        if (config('cms.auth.users.login.force_password', 'false') === 'true') {
            $rules['new_password'] .= '|regex:^(?=\d*)(?=[a-z]*)(?=[A-Z]*)(?=[\W]*).{8,}';
            $rules['new_password_confirmation'] .= '|regex:^(?=\d*)(?=[a-z]*)(?=[A-Z]*)(?=[\W]*).{8,}';
        }

        return $rules;
    }
}
