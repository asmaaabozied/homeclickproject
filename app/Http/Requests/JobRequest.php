<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobRequest extends FormRequest
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
        return [
            'email' => 'required',
            //'email'    => 'max:254|unique:jobs|email|required',
            'phone' => 'required|min:9',
            'name' => 'required',
            'job' => 'required',
            'description' => 'required',
           'user_id'=>'required',
           'image'=>'required'


        ];
    }

    public function messages()
    {
        return [
            'email.required'


        ];
    }
}
