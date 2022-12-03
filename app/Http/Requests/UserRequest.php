<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $uniqueEmail = 'unique:users';

        if (session('id')) {
            $id = session('id');
            $uniqueEmail = 'unique:users,email,'.$id;
        }
        // dd($id);
        return [
            'name' => 'required|min:4',
            'email' => 'required|email|'.$uniqueEmail,
            'group_id' => ['required', 'integer', function($attribute, $value, $fail) {
                if ($value == 0) {
                    $fail('You have not selected a group!');
                }
            }],
            'status' => 'required|integer'
        ];
    }

    public function messages() {
        return [
            'name.required' => 'Enter your name!',
            'name.min' => 'Name must be more than 4 characters!',
            'email.required' => 'Enter your email!',
            'email.email' => 'Invalid email!',
            'email.unique' => 'Email already exists!',
            'group_id.required' => 'Group has not been selected!',
            'group_id.integer' => 'Invalid group!',
            'status.required' => 'Status has not been selected!',
            'status.integer' => 'Invalid status!',
        ];
    }
}
