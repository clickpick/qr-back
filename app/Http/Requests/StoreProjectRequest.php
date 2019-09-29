<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreProjectRequest
 * @package App\Http\Requests
 * @property $name
 * @property $description
 * @property $big_description
 * @property $raised_funds
 * @property $goal_funds
 * @property $prize
 * @property $winners_count
 * @property $poster
 * @property $banner
 */
class StoreProjectRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'big_description' => 'nullable|string',
            'raised_funds' => 'nullable|numeric',
            'goal_funds' => 'nullable|numeric',
            'prize' => 'required|string|max:255',
            'winners_count' => 'nullable|integer',
            'poster' => 'nullable|image',
            'banner' => 'nullable|image',
            'link' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:255',
        ];
    }
}
