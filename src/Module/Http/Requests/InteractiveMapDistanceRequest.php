<?php

namespace RefinedDigital\InteractiveMap\Module\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InteractiveMapDistanceRequest extends FormRequest
{
    /**
     * Determine if the service is authorized to make this request.
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

        $args = [
            'name'                => ['required' => 'required'],
            'distance'                => ['required' => 'required'],
        ];

        // return the results to set for validation
        return $args;
    }
}
