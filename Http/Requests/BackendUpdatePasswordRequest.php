<?php

namespace Cms\Modules\Auth\Http\Requests;

use Cms\Http\Requests\Request;
use Auth;

class BackendUpdatePasswordRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() && Auth::user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required_with:password|min:8',
        ];

        // if we wanted secure passwords...
        if (config('cms.auth.users.login.force_password', 'false') === 'true') {
            $rules['password'] .= '|regex:^(?=\d*)(?=[a-z]*)(?=[A-Z]*)(?=[\W]*).{8,}';
            $rules['password_confirmation'] .= '|regex:^(?=\d*)(?=[a-z]*)(?=[A-Z]*)(?=[\W]*).{8,}';
        }

        return $rules;
    }
}
