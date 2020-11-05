<?php
/**
 * Theqqa - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.
 *
  * Theqqa
 */

namespace Modules\Pages\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageRequest extends  FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

		$rules = [

        ];
        $ignore_id =!empty ( $this->route()->parameters()['page'])?$this->route()->parameters()['page']->id:"";
        foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => ['required', Rule::unique('page_translations', 'name')->ignore($ignore_id,'page_id')]];
            $rules += [$locale . '.title' => ['required']];
            $rules += [$locale . '.content' => ['required']];
        }
		return $rules;

    }

}
