<?php namespace Cms\Modules\Auth\Http\Requests;

use Cms\Http\Requests\Request;
use Auth;

class FrontendSecurityRequest extends Request
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
        return [
            'old_password' => 'required_with:new_password',
            'new_password' => 'required_with:old_password',
            'new_password_confirmation' => 'required_with:old_password,new_password',
        ];
    }
}
