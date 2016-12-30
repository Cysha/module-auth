<?php

namespace Cms\Modules\Auth\Http\Requests;

use Auth;
use Cms\Http\Requests\Request;
use Illuminate\Validation\Rule;

class FrontendSettingsRequest extends Request
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
        $user_id = Auth::id();
        $tblPrefix = config('cms.auth.table-prefix', 'auth_');

        return [
            'username' => ['required', Rule::unique($tblPrefix.'users')->ignore($user_id)],
            'name' => 'required',
            'use_nick' => 'in:0,1',
            'email' => ['required', 'email', Rule::unique($tblPrefix.'users')->ignore($user_id)],
        ];
    }
}
