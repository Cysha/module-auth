<?php namespace Cms\Modules\Auth\Http\Requests;

use Cms\Http\Requests\Request;
use Auth;

class BackendCreateUserRequest extends Request
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
        return [
            'username' => 'required|unique:users,username',
            'name' => 'required',
            'email' => 'required',
        ];
    }
}
