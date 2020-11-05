<?php
/**
 * Theqqa - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace Modules\Notifications\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NotifyRequest extends  FormRequest
{

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

		$rules = [
         'owner_emails' => 'required_without:user_emails|array' ,
            'user_emails' => 'required_without:owner_emails|array' ,
         'body' => 'required' ,
         'title' => 'required' ,
        ];

		return $rules;

    }

}
