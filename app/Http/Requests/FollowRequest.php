<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FollowRequest extends FormRequest
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
            'following_id' => ['required', 'numeric', 'not_in:' . auth()->id(), 'exists:users,id']
        ];
    }

    public function messages()
    {
        return [
            'not_in' => 'You cannot follow yourself.'
        ];
    }
}
