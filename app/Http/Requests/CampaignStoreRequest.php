<?php

namespace peertxt\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CampaignStoreRequest extends FormRequest
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
			'campaign_name' => 'required',
			'zipcode' => 'required',
			'areacode' => 'required',
			'content_template_1' => 'required',
			'tags_list' => 'required_if:campaign_status,1'
		];
	}

	public function messages()
	{
		return [
			'tags_list.required_if' => 'Must select at least one contact tag'
		];
	}
}
