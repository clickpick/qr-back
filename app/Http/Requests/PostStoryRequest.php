<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PostStoryRequest
 * @package App\Http\Requests
 * @property $upload_url
 * @property $image
 */
class PostStoryRequest extends FormRequest
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
            'upload_url' => 'required|string',
            'image' => 'required|string'
        ];
    }
}
